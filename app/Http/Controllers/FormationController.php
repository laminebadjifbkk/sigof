<?php
namespace App\Http\Controllers;

use App\Models\Choixoperateur;
use App\Models\Collective;
use App\Models\Collectivemodule;
use App\Models\Departement;
use App\Models\Domaine;
use App\Models\Emargement;
use App\Models\Emargementcollective;
use App\Models\Evaluateur;
use App\Models\Feuillepresence;
use App\Models\Feuillepresencecollective;
use App\Models\Formation;
use App\Models\Indisponible;
use App\Models\Individuelle;
use App\Models\Ingenieur;
use App\Models\Listecollective;
use App\Models\Module;
use App\Models\Onfpevaluateur;
use App\Models\Operateur;
use App\Models\Operateurmodule;
use App\Models\Programme;
use App\Models\Projet;
use App\Models\Referentiel;
use App\Models\Region;
use App\Models\Statut;
use App\Models\TypesFormation;
use App\Models\Validationformation;
use App\Models\Validationindividuelle;
use Artisan;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FormationController extends Controller
{

    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DIOF|ADIOF|DEC']);
        $this->middleware("permission:formation-view", ["only" => ["index"]]);
        /* $this->middleware(['permission:arrive-show']); */
        // or with specific guard
        /* $this->middleware(['role_or_permission:super-admin']); */
    }

    public function index()
    {
        $formations = Formation::where('statut', '!=', 'supprimer')->orderBy('created_at', 'desc')->get();

        $individuelles_formations_count = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
            ->select('formations.*')
            ->where('types_formations.name', "individuelle")
            ->where('statut', '!=', 'supprimer')
            ->count();

        $collectives_formations_count = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
            ->select('formations.*')
            ->where('types_formations.name', "collective")
            ->where('statut', '!=', 'supprimer')
            ->count();

        $qrcode      = QrCode::size(200)->generate("tel:+221776994173");
        $today       = date('Y-m-d');
        $count_today = Formation::where("created_at", "LIKE", "{$today}%")->count();

        $modules          = Module::orderBy("created_at", "desc")->get();
        $departements     = Departement::orderBy("created_at", "desc")->get();
        $regions          = Region::orderBy("created_at", "desc")->get();
        $operateurs       = Operateur::orderBy("created_at", "desc")->get();
        $projets          = Projet::orderBy("created_at", "desc")->get();
        $programmes       = Programme::orderBy("created_at", "desc")->get();
        $choixoperateurs  = Choixoperateur::orderBy("created_at", "desc")->get();
        $types_formations = TypesFormation::orderBy("created_at", "desc")->get();

        $anneeEnCours = date('Y');
        $annee        = date('y');

        $numFormation = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
            ->select('formations.*')
            ->where('formations.annee', $anneeEnCours)
            ->get()->last();

        /*->where('types_formations.name', $request->types_formation)*/

        if (isset($numFormation)) {
            $numFormation = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
                ->select('formations.*')
                ->where('formations.annee', $anneeEnCours)
                ->get()->last()->code;

            $numFormation = ++$numFormation;

            /*  ->where('types_formations.name', $request->types_formation) */
        } else {
            $numFormation = $annee . "0001";

            $numFormation = 'F' . $numFormation;

            $longueur = strlen($numFormation);

            if ($longueur <= 1) {
                $numFormation = strtoupper("00000" . $numFormation);
            } elseif ($longueur >= 2 && $longueur < 3) {
                $numFormation = strtoupper("0000" . $numFormation);
            } elseif ($longueur >= 3 && $longueur < 4) {
                $numFormation = strtoupper("000" . $numFormation);
            } elseif ($longueur >= 4 && $longueur < 5) {
                $numFormation = strtoupper("00" . $numFormation);
            } elseif ($longueur >= 5 && $longueur < 6) {
                $numFormation = strtoupper("0" . $numFormation);
            } else {
                $numFormation = strtoupper($numFormation);
            }
        }

        /* $title = 'Liste des formations de ' . $anneeEnCours; */
        $title = 'Liste des formations';

        $formations_annee = Formation::distinct()
            ->get('annee');

        $formations_statut = Formation::distinct()
            ->get('statut');

        return view(
            "formations.index",
            compact(
                "qrcode",
                "count_today",
                "collectives_formations_count",
                "individuelles_formations_count",
                "formations",
                "modules",
                "departements",
                "regions",
                "operateurs",
                'types_formations',
                'projets',
                'programmes',
                'numFormation',
                'choixoperateurs',
                'title',
                'formations_annee',
                'formations_statut',
            )
        );
    }

    public function create()
    {
        return view("formations.create");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "code"               => "required|string|min:7|max:8|unique:formations,code",
            "name"               => "required|string|unique:formations,name",
            "departement"        => "required|string",
            "lieu"               => "required|string",
            "type_certification" => "required|string",
            "types_formation"    => "required|string",
            "date_debut"         => "nullable|date|min:10|max:10|date_format:Y-m-d",
            "date_fin"           => "nullable|date|min:10|max:10|date_format:Y-m-d",
        ]);

        /* $mois = date('m');
        $rand1 = rand(100, 999);
        $rand2 = chr(rand(65, 90));

        $rand = $rand1 . '' . $rand2; */

        $types_formation = TypesFormation::where('name', $request->input('types_formation'))->get()->first();
        $departement     = Departement::where('nom', $request->input('departement'))->get()->first();

        /* $count_formation = Formation::get()->count();

        $count_formation = ++$count_formation;

        $longueur = strlen($count_formation);

        if ($longueur == 1) {
        $code_formation   =   strtolower("000" . $count_formation);
        } elseif ($longueur >= 2 && $longueur < 3) {
        $code_formation   =   strtolower("00" . $count_formation);
        } elseif ($longueur >= 3 && $longueur < 4) {
        $code_formation   =   strtolower("0" . $count_formation);
        } else {
        $code_formation   =   strtolower($count_formation);
        }

        if ($types_formation->name == "individuelle") {
        $for = "F";
        $fin = "I";
        } else {
        $for = "F";
        $fin = "C";
        }

        $code_formation = $for . '' . $annee . '' . $code_formation . '' . $fin; */

        $anneeEnCours = date('Y');
        $annee        = date('y');

        $numFormation = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
            ->select('formations.*')
            ->where('formations.annee', $anneeEnCours)
            ->get()->last();

        /*->where('types_formations.name', $request->types_formation)*/

        if (isset($numFormation)) {
            $numFormation = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
                ->select('formations.*')
                ->where('formations.annee', $anneeEnCours)
                ->get()->last()->code;

            $numFormation = ++$numFormation;

            /*  ->where('types_formations.name', $request->types_formation) */
        } else {
            $numFormation = $annee . "0001";

            $numFormation = 'F' . $numFormation;

            $longueur = strlen($numFormation);

            if ($longueur <= 1) {
                $numFormation = strtoupper("00000" . $numFormation);
            } elseif ($longueur >= 2 && $longueur < 3) {
                $numFormation = strtoupper("0000" . $numFormation);
            } elseif ($longueur >= 3 && $longueur < 4) {
                $numFormation = strtoupper("000" . $numFormation);
            } elseif ($longueur >= 4 && $longueur < 5) {
                $numFormation = strtoupper("00" . $numFormation);
            } elseif ($longueur >= 5 && $longueur < 6) {
                $numFormation = strtoupper("0" . $numFormation);
            } else {
                $numFormation = strtoupper($numFormation);
            }
        }

        /* $arrives = Arrive::orderBy('created_at', 'desc')->get(); */

        $total_count = Formation::get();
        $total_count = number_format($total_count->count(), 0, ',', ' ');

        /* $rand = $fic . '' . $mois . $annee . $rand; */

        /*   $this->validate($request, [
        "name"                  =>   "required|string|unique:formations,name,except,id",
        "departement"           =>   "required|string",
        "lieu"                  =>   "required|string",
        "type_certification"  =>   "required|string",
        "titre"                 =>   "nullable|string",
        "date_debut"            =>   "nullable|date",
        "date_fin"              =>   "nullable|date",
        ]); */

        if (! empty($request->input('prevue_h'))) {
            $prevue_h = $request->input('prevue_h');
        } else {
            $prevue_h = null;
        }
        if (! empty($request->input('prevue_f'))) {
            $prevue_f = $request->input('prevue_f');
        } else {
            $prevue_f = null;
        }

        $effectif_prevu = $prevue_h + $prevue_f;

        if (! empty($request->input('date_debut'))) {
            $date_debut = $request->input('date_debut');
        } else {
            $date_debut = null;
        }

        if (! empty($request->input('date_fin'))) {
            $date_fin = $request->input('date_fin');
        } else {
            $date_fin = null;
        }

        if (! empty($request->input('frais_operateurs'))) {
            $frais_operateurs = $request->input('frais_operateurs');
        } else {
            $frais_operateurs = null;
        }

        if (! empty($request->input('frais_add'))) {
            $frais_add = $request->input('frais_add');
        } else {
            $frais_add = null;
        }

        if (! empty($request->input('autes_frais'))) {
            $autes_frais = $request->input('autes_frais');
        } else {
            $autes_frais = null;
        }

        $formation = new Formation([
            "code"                => $request->input('code'),
            "name"                => $request->input('name'),
            "regions_id"          => $departement->region->id,
            "departements_id"     => $departement->id,
            "lieu"                => $request->input('lieu'),
            /* "modules_id"            =>   $request->input('module'), */
            "operateurs_id"       => $request->input('operateur'),
            "types_formations_id" => $types_formation->id,
            "type_certification"  => $request->input('type_certification'),
            /* "numero_convention"     =>   $request->input('numero_convention'), */
            /* "titre"                 =>   $request->input('titre'), */
            "date_debut"          => $date_debut,
            "date_fin"            => $date_fin,
            "effectif_prevu"      => $effectif_prevu,
            "prevue_h"            => $prevue_h,
            "prevue_f"            => $prevue_f,
            "frais_operateurs"    => $frais_operateurs,
            /* "lettre_mission"        =>   $request->input('lettre_mission'), */
            "frais_add"           => $frais_add,
            "autes_frais"         => $autes_frais,
            "projets_id"          => $request->input('projet'),
            "programmes_id"       => $request->input('programme'),
            "choixoperateurs_id"  => $request->input('choixoperateur'),
            "statut"              => "Nouvelle",
            "annee"               => $anneeEnCours,

        ]);

        $formation->save();

        $statut = new Statut([
            "statut"        => "Nouvelle",
            "formations_id" => $formation->id,
        ]);

        $statut->save();

        Alert::success("Bravo !", "La formation a été créée avec succès.");

        return redirect()->back();
    }

    public function edit($id)
    {
        $formation        = Formation::findOrFail($id);
        $departements     = Departement::orderBy("created_at", "desc")->get();
        $types_formations = TypesFormation::orderBy("created_at", "desc")->get();
        $projets          = Projet::orderBy("created_at", "desc")->get();
        $programmes       = Programme::orderBy("created_at", "desc")->get();
        $choixoperateurs  = Choixoperateur::orderBy("created_at", "desc")->get();
        $referentiels     = Referentiel::get();
        $evaluateurs      = Evaluateur::get();
        $onfpevaluateurs  = Onfpevaluateur::get();

        return view(
            "formations.update",
            compact(
                "formation",
                "departements",
                "types_formations",
                'projets',
                'programmes',
                'referentiels',
                'choixoperateurs',
                'evaluateurs',
                'onfpevaluateurs',
            )
        );
    }

    public function update(Request $request, $id)
    {
        $formation = Formation::findOrFail($id);

        $this->validate($request, [
            "code"               => "required|string|unique:formations,code,{$formation->id}",
            "name"               => "required|string|unique:formations,name,{$formation->id}",
            "departement"        => "required|string",
            "lieu"               => "required|string",
            "type_certification" => "required|string",
            "titre"              => "nullable|string",
            "date_debut"         => "nullable|date|min:10|max:10|date_format:Y-m-d",
            "date_convention"    => "nullable|date|min:10|max:10|date_format:Y-m-d",
            "date_lettre"        => "nullable|date|min:10|max:10|date_format:Y-m-d",
            "date_fin"           => "nullable|date|min:10|max:10|date_format:Y-m-d",
            "lettre_mission"     => "nullable|string",
            "annee"              => "nullable|numeric",
            "file_convention"    => ['sometimes', 'file', 'mimes:pdf', 'max:2048'],
            "detf-file"          => ['sometimes', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        if (! empty($request->input('prevue_h'))) {
            $prevue_h = $request->input('prevue_h');
        } else {
            $prevue_h = null;
        }
        if (! empty($request->input('prevue_f'))) {
            $prevue_f = $request->input('prevue_f');
        } else {
            $prevue_f = null;
        }

        $effectif_prevu = $prevue_h + $prevue_f;

        $projet = Projet::where('sigle', $request->input('projet'))->first();

        $referentiel = Referentiel::where('titre', $request->titre)->first();

        if (! empty($referentiel) && $request->titre != 'Renforcement de capacités') {
            $referentiel_id = $referentiel?->id;
            $titre          = null;
            $type           = 'Titre';
        } elseif ($request->titre == 'Renforcement de capacités') {
            $referentiel_id = null;
            $titre          = 'Renforcement de capacités';
            $type           = 'Attestation';
        } else {
            $referentiel_id = null;
            $titre          = null;
            $type           = null;
        }

        if (request('file_convention') && ! empty($formation->file_convention)) {
            Storage::disk('public')->delete($formation->file_convention);
            $filePath = request('file_convention')->store('conventions', 'public');
            $file     = $request->file('file_convention');
            $formation->update([
                'file_convention' => $filePath,
            ]);

            $formation->save();

        } elseif (request('file_convention') && empty($formation->file_convention)) {
            $filePath = request('file_convention')->store('conventions', 'public');
            $file     = $request->file('file_convention');
            $formation->update([
                'file_convention' => $filePath,
            ]);

            $formation->save();
        }

        if (request('detf_file') && ! empty($formation->detf_file)) {
            Storage::disk('public')->delete($formation->detf_file);
            $filePath = request('detf_file')->store('detfs', 'public');
            $file     = $request->file('detf_file');
            $formation->update([
                'detf_file' => $filePath,
            ]);

            $formation->save();

        } elseif (request('detf_file') && empty($formation->detf_file)) {
            $filePath = request('detf_file')->store('detfs', 'public');
            $file     = $request->file('detf_file');
            $formation->update([
                'detf_file' => $filePath,
            ]);

            $formation->save();
        }

        if (request('file_pv') && ! empty($formation->file_pv)) {
            Storage::disk('public')->delete($formation->file_pv);
            $filePath = request('file_pv')->store('pvs', 'public');
            $file     = $request->file('file_pv');
            $formation->update([
                'file_pv' => $filePath,
            ]);

            $formation->save();

        } elseif (request('file_pv') && empty($formation->file_pv)) {
            $filePath = request('file_pv')->store('pvs', 'public');
            $file     = $request->file('file_pv');
            $formation->update([
                'file_pv' => $filePath,
            ]);

            $formation->save();
        }

        if (request('abe_file') && ! empty($formation->abe_file)) {
            Storage::disk('public')->delete($formation->abe_file);
            $filePath = request('abe_file')->store('abe', 'public');
            $file     = $request->file('abe_file');
            $formation->update([
                'abe_file' => $filePath,
            ]);

            $formation->save();

        } elseif (request('abe_file') && empty($formation->abe_file)) {
            $filePath = request('abe_file')->store('abe', 'public');
            $file     = $request->file('abe_file');
            $formation->update([
                'abe_file' => $filePath,
            ]);

            $formation->save();
        }

        if (request('lettre_mission_file') && ! empty($formation->lettre_mission_file)) {
            Storage::disk('public')->delete($formation->lettre_mission_file);
            $filePath = request('lettre_mission_file')->store('lm', 'public');
            $file     = $request->file('lettre_mission_file');
            $formation->update([
                'lettre_mission_file' => $filePath,
            ]);

            $formation->save();

        } elseif (request('lettre_mission_file') && empty($formation->lettre_mission_file)) {
            $filePath = request('lettre_mission_file')->store('lm', 'public');
            $file     = $request->file('lettre_mission_file');
            $formation->update([
                'lettre_mission_file' => $filePath,
            ]);

            $formation->save();
        }

        if (! empty($request->input('date_debut'))) {
            $date_debut = date('Y-m-d H:i:s', strtotime($request->input('date_debut')));
        } else {
            $date_debut = null;
        }

        if (! empty($request->input('date_fin'))) {
            $date_fin = date('Y-m-d H:i:s', strtotime($request->input('date_fin')));
        } else {
            $date_fin = null;
        }

        if (! empty($request->input('date_pv'))) {
            $date_pv = date('Y-m-d H:i:s', strtotime($request->input('date_pv')));
        } else {
            $date_pv = null;
        }

        if (! empty($request->input('date_lettre'))) {
            $date_lettre = date('Y-m-d H:i:s', strtotime($request->input('date_lettre')));
        } else {
            $date_lettre = null;
        }

        if (! empty($request->input('date_convention'))) {
            $date_convention = date('Y-m-d H:i:s', strtotime($request->input('date_convention')));
        } else {
            $date_convention = null;
        }

        if (! empty($request->input('frais_operateurs'))) {
            $frais_operateurs = $request->input('frais_operateurs');
        } else {
            $frais_operateurs = null;
        }
        if (! empty($request->input('frais_add'))) {
            $frais_add = $request->input('frais_add');
        } else {
            $frais_add = null;
        }
        if (! empty($request->input('autes_frais'))) {
            $autes_frais = $request->input('autes_frais');
        } else {
            $autes_frais = null;
        }

        if (! empty($request->input('frais_evaluateur'))) {
            $frais_evaluateur = $request->input('frais_evaluateur');
        } else {
            $frais_evaluateur = null;
        }
        if (! empty($request->input('onfpevaluateur'))) {
            $onfpevaluateur = $request->input('onfpevaluateur');
        } else {
            $onfpevaluateur = null;
        }
        if (! empty($request->input('duree_formation'))) {
            $duree_formation = $request->input('duree_formation');
        } else {
            $duree_formation = null;
        }

        if (! empty($request->input('date_etat'))) {
            $date_etat = date('Y-m-d H:i:s', strtotime($request->input('date_etat')));
        } else {
            $date_etat = null;
        }

        if (! empty($request->input('indemnite_transport_jour'))) {
            $indemnite_transport_jour = $request->input('indemnite_transport_jour');
        } else {
            $indemnite_transport_jour = null;
        }

        $formation->update([
            "code"                     => $request->input('code'),
            "name"                     => $request->input('name'),
            "regions_id"               => $request->input('region'),
            "departements_id"          => $request->input('departement'),
            "lieu"                     => $request->input('lieu'),
            "types_formations_id"      => $request->input('types_formation'),
            "type_certification"       => $request->input('type_certification'),
            "numero_convention"        => $request->input('numero_convention'),
            "titre"                    => $titre,
            "type_certificat"          => $type,
            "date_debut"               => $date_debut,
            "date_fin"                 => $date_fin,
            "date_convention"          => $date_convention,
            "date_lettre"              => $date_lettre,
            "effectif_prevu"           => $effectif_prevu,
            "prevue_h"                 => $prevue_h,
            "prevue_f"                 => $prevue_f,
            "frais_operateurs"         => $frais_operateurs,
            "frais_add"                => $frais_add,
            "autes_frais"              => $autes_frais,
            "projets_id"               => $projet?->id,
            "lettre_mission"           => $request->input('lettre_mission'),
            "programmes_id"            => $request->input('programme'),
            "choixoperateurs_id"       => $request->input('choixoperateur'),
            "referentiels_id"          => $referentiel_id,
            "annee"                    => $request->input('annee'),
            "membres_jury"             => $request->input('membres_jury'),
            "frais_evaluateur"         => $frais_evaluateur,
            "recommandations"          => $request->input('recommandations'),
            "date_pv"                  => $date_pv,
            /* "evaluateurs_id"        =>   $request->input('evaluateur'), */
            "onfpevaluateurs_id"       => $onfpevaluateur,
            "attestation"              => $request->statut,
            "duree_formation"          => $duree_formation,
            "date_etat"                => $date_etat,
            "indemnite_transport_jour" => $indemnite_transport_jour,

        ]);

        $formation->save();

        Alert::success("Modification réussie", "La formation a été modifiée avec succès.");

        return redirect()->back();
    }

    public function show($id)
    {
        $formation         = Formation::findOrFail($id);
        $type_formation    = $formation?->types_formation->name;
        $operateur         = $formation?->operateur;
        $module            = $formation?->module;
        $module_collective = $formation?->collectivemodule;
        $ingenieur         = $formation?->ingenieur;
        /* $emargements = $formation->emargements->unique('jour'); */
        $emargements           = $formation->emargements;
        $emargementcollectives = $formation->emargementcollectives;

        $count_demandes = count($formation->individuelles);

        $individuelles    = Individuelle::orderBy("created_at", "desc")->get();
        $listecollectives = Listecollective::orderBy("created_at", "desc")->get();
        $evaluateurs      = Evaluateur::orderBy("created_at", "desc")->get();
        $onfpevaluateurs  = Onfpevaluateur::orderBy("created_at", "desc")->get();

        $collectivemodule = Collectivemodule::where('collectives_id', $formation->collectives_id)->get();
        $referentiels     = Referentiel::get();

        $collectiveFormation = DB::table('formations')
            ->where('collectivemodules_id', $formation->collectivemodules_id)
            ->pluck('collectivemodules_id', 'collectivemodules_id')
            ->all();

        $collectivemodules = Collectivemodule::join('collectives', 'collectives.id', 'collectivemodules.collectives_id')
            ->select('collectivemodules.*')
            ->where('collectives.statut_demande', 'Attente')
            ->orwhere('collectivemodules.statut', ['Retenu'])
            ->orwhere('collectivemodules.statut', ['Retiré'])
            ->orwhere('collectivemodules.statut', ['formés'])
            ->get();

        $collectiveModule = DB::table('collectivemodules')
            ->where('formations_id', $formation->id)
            ->pluck('formations_id', 'formations_id')
            ->all();

        $collectiveModuleCheck = DB::table('collectivemodules')
            ->where('formations_id', '!=', null)
            ->where('formations_id', '!=', $formation->id)
            ->pluck('formations_id', 'formations_id')
            ->all();

        return view(
            'formations.' . $type_formation . "s.show",
            compact(
                "evaluateurs",
                "formation",
                "count_demandes",
                "operateur",
                "module",
                "module_collective",
                "type_formation",
                "individuelles",
                "listecollectives",
                "collectiveFormation",
                "onfpevaluateurs",
                "ingenieur",
                "collectivemodules",
                "collectiveModule",
                "collectiveModuleCheck",
                "referentiels",
                "emargementcollectives",
                "emargements",
            )
        );
    }

    public function destroy($id)
    {
        $formation = Formation::find($id);

        if (! empty($formation->types_formation->name) && $formation->types_formation->name == "collective") {
            foreach ($formation->listecollectives as $liste) {
            }
            if (! empty($liste)) {
                Alert::warning('Avertissement !', 'La suppression est impossible.');
                return redirect()->back();
            } else {
                $formation->update([
                    "code" => $formation->code . '/' . $formation->id,
                ]);

                $formation->save();

                if (! empty($formation->file_convention)) {
                    Storage::disk('public')->delete($formation->file_convention);
                }

                if (! empty($formation->detf_file)) {
                    Storage::disk('public')->delete($formation->detf_file);
                }

                if (! empty($formation->file_pv)) {
                    Storage::disk('public')->delete($formation->file_pv);
                }

                if (! empty($formation->lettre_mission_file)) {
                    Storage::disk('public')->delete($formation->lettre_mission_file);
                }

                if (! empty($formation->abe_file)) {
                    Storage::disk('public')->delete($formation->abe_file);
                }

                $formation->delete();

                Alert::success('Opération réussie !', 'La formation a été supprimée avec succès.');
                return redirect()->back();
            }
        } elseif (! empty($formation->types_formation->name) && $formation->types_formation->name == "individuelle") {
            foreach ($formation->individuelles as $individuelle) {
            }
            if (! empty($individuelle)) {
                Alert::warning('Avertissement !', 'La suppression est impossible.');
                return redirect()->back();
            } else {
                $formation->update([
                    "code" => $formation->code . '/' . $formation->id,
                ]);

                $formation->save();

                if (! empty($formation->file_convention)) {
                    Storage::disk('public')->delete($formation->file_convention);
                }

                if (! empty($formation->detf_file)) {
                    Storage::disk('public')->delete($formation->detf_file);
                }

                if (! empty($formation->file_pv)) {
                    Storage::disk('public')->delete($formation->file_pv);
                }

                if (! empty($formation->lettre_mission_file)) {
                    Storage::disk('public')->delete($formation->lettre_mission_file);
                }

                if (! empty($formation->abe_file)) {
                    Storage::disk('public')->delete($formation->abe_file);
                }

                $formation->delete();

                Alert::success('Opération réussie !', 'La formation a été supprimée avec succès.');

                return redirect()->back();
            }
        } else {
            $formation->update([
                "statut" => "supprimer",
            ]);

            $formation->save();

            $statut = new Statut([
                "statut"        => "supprimer",
                "formations_id" => $formation->id,
            ]);

            $statut->save();

            Alert::success('Opération réussie !', 'La formation a été supprimée avec succès.');

            return redirect()->back();
        }
    }

    public function addformationdemandeurs($idformation, $idmodule, $idlocalite)
    {
        $formation = Formation::findOrFail($idformation);
        $module    = Module::findOrFail($idmodule);
        $region    = Region::findOrFail($idlocalite);

        if (! empty($formation?->projets_id)) {
            $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('individuelles.projets_id', $formation?->projets_id)
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->where('individuelles.statut', 'Attente')
            /* ->orwhere('individuelles.statut', 'Retirée')
                ->orwhere('individuelles.statut', 'Retenue') */
                ->get();

            $retirer_individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('individuelles.projets_id', $formation?->projets_id)
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->where('individuelles.statut', 'Retirée')
                ->get();
        } else {
            $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->where('individuelles.statut', 'Attente')
            /* ->orwhere('individuelles.statut', 'Retirée')
                ->orwhere('individuelles.statut', 'Retenue') */
                ->get();
            $retirer_individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->where('individuelles.statut', 'Retirée')
                ->get();
        }

        $candidatsretenus = Individuelle::where('formations_id', $idformation)
            ->get();

        $individuelleFormation = DB::table('individuelles')
            ->where('formations_id', $idformation)
            ->pluck('formations_id', 'formations_id')
            ->all();

        $individuelleFormationCheck = DB::table('individuelles')
            ->where('formations_id', '!=', null)
            ->where('formations_id', '!=', $idformation)
            ->pluck('formations_id', 'formations_id')
            ->all();

        return view(
            "formations.individuelles.add-individuelles",
            compact(
                'formation',
                'individuelles',
                'individuelleFormation',
                'module',
                'region',
                'candidatsretenus',
                'retirer_individuelles',
                'individuelleFormationCheck'
            )
        );
    }

    public function giveformationdemandeurs($idformation, $idmodule, $idlocalite, Request $request)
    {
        $request->validate([
            'individuelles' => ['required'],
        ]);

        $formation = Formation::findOrFail($idformation);

        if ($formation->statut == 'Terminée') {
            Alert::warning('Désolé !', 'Cette formation a déjà été exécutée.');
        } elseif ($formation->statut == 'Annulée') {
            Alert::warning('Désolé !', 'La formation a été annulée.');
        } else {
            foreach ($request->individuelles as $individuelle) {
                $individuelle = Individuelle::findOrFail($individuelle);
                $individuelle->update([
                    "formations_id" => $idformation,
                    "statut"        => 'Retenue',
                ]);

                $individuelle->save();
            }

            $validated_by = new Validationindividuelle([
                'validated_id'     => Auth::user()->id,
                'action'           => 'Retenue',
                'individuelles_id' => $individuelle->id,
            ]);

            $validated_by->save();

            Alert::success('Opération réussie !', 'Le(s) candidat(s) a/ont été ajouté(s) avec succès.');
        }

        return redirect()->back();
    }

    public function giveindisponibles(Request $request, $idformation)
    {
        $request->validate([
            'motif' => ['required'],
        ]);

        $individuelle = Individuelle::findOrFail($request->input('individuelleid'));
        $formation    = Formation::findOrFail($idformation);

        $date = date('d');
        $date = $date . ' ' . date('m');
        $date = $date . ' ' . date('Y');
        $date = $date . ' à ' . date('H') . 'h';
        $date = $date . ' ' . date('i') . 'min';
        $date = $date . ' ' . date('s') . 's';

        if ($formation->statut == "Terminée" && $individuelle->note_obtenue > 0) {
            Alert::warning('Avertissement !', 'Ce demandeur ne peut pas être retiré.');
        } else {
            $individuelle->update([
                "formations_id" => null,
                "statut"        => 'Retirée',
                "motif_rejet"   => $individuelle->motif_rejet
                . ' retiré de la formation ' . $formation->name
                . ', le ' . $date . ' par ' . Auth::user()->firstname
                . ' pour motif : ' . $request->input('motif')
                . ' ' . Auth::user()->name . ';',
            ]);

            $individuelle->save();

            $indisponible = new Indisponible([
                "motif"            => $request->input('motif'),
                "individuelles_id" => $request->input('individuelleid'),
                "formations_id"    => $idformation,
            ]);

            $indisponible->save();

            $validated_by = new Validationindividuelle([
                'validated_id'     => Auth::user()->id,
                'action'           => 'Retirée',
                'motif'            => $request->input('motif') . ', pour la formation : ' . $formation->name,
                'individuelles_id' => $individuelle->id,
            ]);

            $validated_by->save();

            Alert::success('Opération réussie', 'Le demandeur a été retiré de cette formation.');
        }
        return redirect()->back();
    }
    public function givedisponibles($id, Request $request)
    {
        $request->validate([
            'motif' => ['required'],
        ]);

        $individuelle = Individuelle::findOrFail($id);

        $date = date('d');
        $date = $date . ' ' . date('m');
        $date = $date . ' ' . date('Y');
        $date = $date . ' à ' . date('H') . 'h';
        $date = $date . ' ' . date('i') . 'min';
        $date = $date . ' ' . date('s') . 's';

        $individuelle->update([
            "statut"      => 'Attente',
            "motif_rejet" => 'Remis en attente le '
            . $date . ' par ' . Auth::user()->firstname
            . ' ' . Auth::user()->name . ';',
        ]);

        $individuelle->save();

        $validated_by = new Validationindividuelle([
            'validated_id'     => Auth::user()->id,
            'action'           => 'Attente',
            'motif'            => $request->input('motif'),
            'individuelles_id' => $individuelle->id,
        ]);

        $validated_by->save();

        Alert::success('Opération réussie', 'Le demandeur est maintenant éligible.');

        return redirect()->back();
    }

    public function givecollectiveindisponibles($idformation, Request $request)
    {
        $request->validate([
            'motif' => ['required'],
        ]);

        $listecollective = Listecollective::findOrFail($request->input('listecollectiveid'));
        $formation       = Formation::findOrFail($idformation);

        $date = date('d');
        $date = $date . ' ' . date('m');
        $date = $date . ' ' . date('Y');
        $date = $date . ' à ' . date('H') . 'h';
        $date = $date . ' ' . date('i') . 'min';
        $date = $date . ' ' . date('s') . 's';

        if ($formation->statut == "Terminée" && $listecollective->note_obtenue > 0) {
            Alert::warning('Avertissement !', 'Ce demandeur ne peut pas être retiré.');
        } else {
            $listecollective->update([
                "formations_id" => null,
                "statut"        => 'Retirée',
                "motif_rejet"   => $request->motif . ' '
                . $date . ' par ' . Auth::user()->firstname
                . ' ' . Auth::user()->name . ';',
            ]);

            $listecollective->save();

            Alert::success('Opération réussie', 'Le demandeur a été retiré de cette formation.');
        }
        return redirect()->back();
    }

    public function giveremiseAttestations($idformation, Request $request)
    {
        $request->validate([
            'statut' => ['required'],
        ]);

        $formation = Formation::findOrFail($request->input('formationid'));

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
        } else {
            $formation->update([
                "attestation" => $request->statut,
            ]);

            $formation->save();
        }
        Alert::success('Attestations ' . $request->statut);
        return redirect()->back();
    }

    public function addformationoperateurs($idformation, $idmodule, $idlocalite)
    {
        $formation  = Formation::findOrFail($idformation);
        $module     = Module::findOrFail($idmodule);
        $localite   = Region::findOrFail($idlocalite);
        $modulename = $module->name;

        $operateurs = Operateur::get();

        /* $operateurmodules   =   DB::table('operateurmodules')
        ->where('module', $modulename)
        ->pluck('module', 'module')
        ->all(); */

        $operateurmodules = Operateurmodule::where('module', 'LIKE', "%{$modulename}%")->where('statut', 'agréer')->get();

        $operateurFormation = DB::table('formations')
            ->where('operateurs_id', $formation->operateurs_id)
            ->pluck('operateurs_id', 'operateurs_id')
            ->all();

        return view("formations.individuelles.add-operateurs", compact('formation', 'operateurs', 'operateurmodules', 'module', 'localite', 'operateurFormation'));
    }

    public function giveformationoperateurs($idformation, $idmodule, $idlocalite, Request $request)
    {
        $request->validate([
            'operateur' => ['required'],
        ]);

        $formation = Formation::findOrFail($idformation);

        $formation->update([
            "operateurs_id" => $request->input('operateur'),
        ]);

        $formation->save();

        Alert::success('Opérateur', 'ajouté avec succès');

        return redirect()->back();
    }

    public function addformationcollectiveoperateurs($idformation, $idcollectivemodule, $idlocalite)
    {
        $formation        = Formation::findOrFail($idformation);
        $collectivemodule = Collectivemodule::findOrFail($idcollectivemodule);
        $localite         = Region::findOrFail($idlocalite);
        $modulename       = $collectivemodule->module;

        $operateurs = Operateur::get();

        $operateurmodules = Operateurmodule::where('module', $modulename)->where('statut', 'agréer')->get();

        $operateurFormation = DB::table('formations')
            ->where('operateurs_id', $formation->operateurs_id)
            ->pluck('operateurs_id', 'operateurs_id')
            ->all();

        return view("formations..collectives.add-operateur-collective", compact('formation', 'operateurs', 'operateurmodules', 'collectivemodule', 'localite', 'operateurFormation'));
    }

    public function giveformationcollectiveoperateurs($idformation, $idcollectivemodule, $idlocalite, Request $request)
    {
        $request->validate([
            'operateur' => ['required'],
        ]);

        $formation = Formation::findOrFail($idformation);

        $formation->update([
            "operateurs_id" => $request->input('operateur'),
        ]);

        $formation->save();

        Alert::success('Opérateur', 'ajouté avec succès');

        return redirect()->back();
    }

    public function addformationmodules($idformation, $idlocalite)
    {
        $formation = Formation::findOrFail($idformation);
        $module    = $formation?->module?->name;
        $domaines  = Domaine::orderBy("created_at", "desc")->get();
        $localite  = Region::findOrFail($idlocalite);

        $modules = Module::get();

        $moduleFormation = DB::table('formations')
            ->where('modules_id', $formation->modules_id)
            ->pluck('modules_id', 'modules_id')
            ->all();

        return view("formations.individuelles.add-modules-individuelles", compact('formation', 'modules', 'module', 'localite', 'moduleFormation', 'domaines'));
    }

    public function addformationcollectivemodules($idformation, $idlocalite)
    {
        $formation = Formation::findOrFail($idformation);
        $domaines  = Domaine::orderBy("created_at", "desc")->get();
        $localite  = Region::findOrFail($idlocalite);

        $collectivemodules = Collectivemodule::get();

        $collectivemoduleFormation = DB::table('formations')
            ->where('collectivemodules_id', $formation->collectivemodules_id)
            ->pluck('collectivemodules_id', 'collectivemodules_id')
            ->all();

        return view("formations.collectives.add-collective-modules", compact('formation', 'collectivemodules', 'localite', 'collectivemoduleFormation', 'domaines'));
    }

    public function giveformationmodules($idformation, Request $request)
    {
        $request->validate([
            'module' => ['required'],
        ]);

        $formation = Formation::findOrFail($idformation);

        $formation->update([
            "modules_id" => $request->input('module'),
        ]);

        $formation->save();

        Alert::success('Module', 'ajouté avec succès');

        return redirect()->back();
    }

    public function giveformationcollectivemodules($idformation, Request $request)
    {
        $request->validate([
            'collectivemodule' => ['required'],
        ]);

        $formation        = Formation::findOrFail($idformation);
        $collectivemodule = Collectivemodule::findOrFail($request->input('collectivemodule'));
        $collective       = $collectivemodule?->collective;

        $collectivemodule->update([
            "statut" => 'Retenue',
        ]);

        $collectivemodule->save();

        $formation->update([
            "collectivemodules_id" => $request->input('collectivemodule'),
        ]);

        $formation->save();

        $collective->update([
            "formations_id" => $formation?->id,
        ]);

        $collective->save();

        Alert::success('Module', 'ajouté avec succès');

        return redirect()->back();

    }

    public function addmoduleformations($idformation, $idlocalite)
    {

        $formation = Formation::findOrFail($idformation);
        /* $module    = $formation?->module?->name; */
        $localite = Region::findOrFail($idlocalite);

        $modules = Module::get();

        $moduleFormation = DB::table('formations')
            ->where('modules_id', $formation->modules_id)
            ->pluck('modules_id', 'modules_id')
            ->all();

        $domaines = Domaine::orderBy("created_at", "desc")->get();

        return view("formations.individuelles.add-modules-individuelles", compact('formation', 'modules', 'localite', 'moduleFormation', 'domaines'));
    }

    public function addcollectivemoduleformations($idformation, $idlocalite)
    {

        $formation = Formation::findOrFail($idformation);
        $localite  = Region::findOrFail($idlocalite);

        /* $collectivemodule    = $formation?->collectivemodule?->module; */

        $collectivemodules = Collectivemodule::get();

        $collectivemoduleFormation = DB::table('formations')
            ->where('collectivemodules_id', $formation->collectivemodules_id)
            ->pluck('collectivemodules_id', 'collectivemodules_id')
            ->all();

        $domaines = Domaine::orderBy("created_at", "desc")->get();

        return view("formations.collectives.add-collective-modules", compact('formation', 'collectivemodules', 'localite', 'collectivemoduleFormation', 'domaines'));
    }

    public function addformationingenieurs($idformation)
    {
        $formation = Formation::findOrFail($idformation);
        $ingenieur = $formation?->ingenieur?->name;

        $ingenieurs = Ingenieur::get();

        $ingenieurFormation = DB::table('formations')
            ->where('ingenieurs_id', $formation->ingenieurs_id)
            ->pluck('ingenieurs_id', 'ingenieurs_id')
            ->all();

        $domaines = Domaine::orderBy("created_at", "desc")->get();

        return view("formations.add-ingenieur", compact('formation', 'ingenieurs', 'ingenieur', 'ingenieurFormation', 'domaines'));
    }

    public function giveformationingenieurs($idformation, Request $request)
    {
        $request->validate([
            'ingenieur' => ['required'],
        ]);

        $formation = Formation::findOrFail($idformation);

        $formation->update([
            "ingenieurs_id" => $request->input('ingenieur'),
        ]);

        $formation->save();

        Alert::success('Ingenieur', 'ajouté avec succès');

        return redirect()->back();
    }

    public function addcollectiveformations($idformation, $idlocalite)
    {
        $formation = Formation::findOrFail($idformation);

        $collectivemodules = Collectivemodule::join('collectives', 'collectives.id', 'collectivemodules.collectives_id')
            ->select('collectivemodules.*')
            ->where('collectives.statut_demande', 'Attente')
            ->where('collectivemodules.statut', 'Attente')
            ->orwhere('collectivemodules.statut', 'Retenue')
            ->get();

        $collectiveModule = DB::table('collectivemodules')
            ->where('formations_id', $idformation)
            ->pluck('formations_id', 'formations_id')
            ->all();

        $collectiveModuleCheck = DB::table('collectivemodules')
            ->where('formations_id', '!=', null)
            ->orwhere('formations_id', '!=', $idformation)
            ->pluck('formations_id', 'formations_id')
            ->all();

        return view(
            "formations.collectives.add-collectives",
            compact(
                'formation',
                'collectivemodules',
                'collectiveModule',
                'collectiveModuleCheck'
            )
        );
    }

    public function giveformationcollectives($idformation, Request $request)
    {
        $request->validate([
            'collectivemodule' => ['required'],
        ]);

        $collectivemodule = Collectivemodule::findOrFail($request->collectivemodule);

        $formation = Formation::findOrFail($idformation);

        if (count($formation->listecollectives) > 0) {
            if (! empty($request->collectivemodule) && $request->collectivemodule != $collectivemodule->id) {

                $collectivemodule->update([
                    "formations_id" => null,
                    "statut"        => 'Attente',
                ]);

                $collectivemodule->save();

                $collectivemodule->update([
                    "formations_id" => $idformation,
                    "statut"        => 'Retenue',
                ]);

                $collectivemodule->save();

                Alert::success('Fait !', 'ajouté avec succès');

                return redirect()->back();
            } else {

                $collectivemodule->update([
                    "formations_id" => $idformation,
                    "statut"        => 'Retenue',
                ]);

                $collectivemodule->save();

                Alert::success('Fait !', 'ajouté avec succès');

                return redirect()->back();
            }

        } else {
            $collectivemodule->update([
                "formations_id" => $idformation,
                "statut"        => 'Retenue',
            ]);

            $collectivemodule->save();

            Alert::success('Fait !', 'ajouté avec succès');

            return redirect()->back();
        }

    }

    public function retirermoduleformation(Request $request, $id)
    {

        $request->validate([
            'motif' => ['required'],
        ]);

        $collectivemodule = Collectivemodule::findOrFail($id);

        $collectivemodule->update([
            "formations_id" => null,
            "statut"        => 'Attente',
        ]);

        $collectivemodule->save();

        Alert::success('Succès !', 'module retiré avec succès');

        return redirect()->back();
    }

    public function givemoduleformationcollectives($idformation, Request $request)
    {
        $request->validate([
            'collectivemodule' => ['required'],
        ]);

        $formation = Formation::findOrFail($idformation);

        $formation->update([
            "collectivemodules_id" => $request->input("collectivemodule"),
        ]);

        $formation->save();

        Alert::success('Fait !', 'ajouté avec succès');

        return redirect()->back();
    }

    public function givemoduleformations($idformation, $idlocalite, Request $request)
    {
        $request->validate([
            'module' => ['required'],
        ]);

        $formation = Formation::findOrFail($idformation);

        $formation->update([
            "modules_id" => $request->input('module'),
        ]);

        $formation->save();

        Alert::success('Module', 'ajouté avec succès');

        return redirect()->back();
    }

    public function formationTerminer(Request $request)
    {
        $formation = Formation::findOrFail($request->input('id'));

        $type = $formation->types_formation->name;

        if ($type == 'collective') {
            $count = $formation->listecollectives->count();
        } elseif ($type == 'individuelle') {
            $count = $formation->individuelles->count();
        } else {
            $count = 0;
        }

        if ($count == '0' || empty($formation->operateur)) {
            Alert::warning('Désolez !', 'action non autorisée');
        } else {

            /* if ($formation->statut == "Terminée") {
            Alert::warning('Désolé !', 'Cette formation a déjà été exécutée.');
            } else */

            if ($formation->statut == 'Annulée') {
                Alert::warning('Désolez !', 'formation déjà annulée');
            } elseif ($formation->statut == 'Attente') {
                Alert::warning('Désolez !', 'la formation n\'a pas encore démarrée');
            } else {

                if ($formation->types_formation?->name == 'collective') {

                    $admis = Listecollective::where('formations_id', $formation->id)
                        ->where('note_obtenue', '>=', '12')
                        ->count();

                    $formes_h_count = Listecollective::where('formations_id', $formation->id)
                        ->count();

                    $formes_f_count = $formes_h_count;
                } else {

                    $admis = Individuelle::where('formations_id', $formation->id)
                        ->where('note_obtenue', '>=', '12')
                        ->count();

                    $formes_h_count = Individuelle::join('users', 'users.id', 'individuelles.users_id')
                        ->select('individuelles.*')
                        ->where('formations_id', $formation->id)
                        ->where('users.civilite', "M.")
                        ->count();

                    $formes_f_count = Individuelle::join('users', 'users.id', 'individuelles.users_id')
                        ->select('individuelles.*')
                        ->where('formations_id', $formation->id)
                        ->where('users.civilite', "Mme")
                        ->count();
                }

                /* $recales = Individuelle::where('formations_id', $formation->id)
                ->where('note_obtenue', '<', '12')
                ->get();

                $admis_h_count = Individuelle::join('users', 'users.id', 'individuelles.users_id')
                ->select('individuelles.*')
                ->where('formations_id', $formation->id)
                ->where('users.civilite', "M.")
                ->where('note_obtenue', '>=', '12')
                ->count();

                $admis_f_count = Individuelle::join('users', 'users.id', 'individuelles.users_id')
                ->select('individuelles.*')
                ->where('formations_id', $formation->id)
                ->where('users.civilite', "Mme")
                ->where('note_obtenue', '>=', '12')
                ->count(); */

                $formes_total = $formes_h_count + $formes_f_count;

                $formation->update([
                    'statut'       => "Terminée",
                    'forme_h'      => $formes_h_count,
                    'forme_f'      => $formes_f_count,
                    'total'        => $formes_total,
                    'nbre_admis'   => $admis,
                    'validated_by' => Auth::user()->firstname . ' ' . Auth::user()->name,
                ]);

                $formation->save();

                $validated_by = new Validationformation([
                    'validated_id'  => Auth::user()->id,
                    'action'        => "Terminée",
                    'formations_id' => $formation->id,
                ]);

                $validated_by->save();

                Alert::success('Félicitation !', 'formation terminée');
            }
        }

        /* return redirect()->back()->with("status", "Demande validée"); */
        return redirect()->back();
    }
    public function formationcollectiveTerminer(Request $request)
    {
        $formation = Formation::findOrFail($request->input('id'));

        $count = $formation->listecollectives->count();

        if ($count == '0' || empty($formation->operateur)) {
            Alert::warning('Désolez !', 'action non autorisée');
        } else {
            if ($formation->statut == "Terminée") {
                Alert::warning('Désolé !', 'Cette formation a déjà été exécutée.');
            } elseif ($formation->statut == "Démarrée") {
                Alert::warning('Désolez !', 'formation en cours...');
            } else {
                $formation->update([
                    'statut'       => "Démarrée",
                    'validated_by' => Auth::user()->firstname . ' ' . Auth::user()->name,
                ]);

                $formation->save();

                $validated_by = new Validationformation([
                    'validated_id'  => Auth::user()->id,
                    'action'        => "Démarrée",
                    'formations_id' => $formation->id,
                ]);

                $validated_by->save();

                Alert::success('Bravo !', 'La formation est maintenant lancée.');
            }
        }

        /* return redirect()->back()->with("status", "Demande validée"); */
        return redirect()->back();
    }
    public function givenotedemandeurs($idformation, Request $request)
    {

        $request->validate([
            'notes' => ['required'],
        ]);

        $individuelles = $request->individuelles;
        $notes         = $request->notes;

        $individuelles_notes = array_combine($individuelles, $notes);

        foreach ($individuelles_notes as $key => $value) {
            $individuelle = Individuelle::findOrFail($key);
            if ($value <= 4) {
                $appreciation = "Médiocre";
            } elseif ($value <= 8) {
                $appreciation = "Insuffisant ";
            } elseif ($value <= 11) {
                $appreciation = "Passable ";
            } elseif ($value <= 13) {
                $appreciation = "Assez bien";
            } elseif ($value <= 16) {
                $appreciation = "Bien";
            } elseif ($value <= 19) {
                $appreciation = "Très bien";
            } elseif ($value = 20) {
                $appreciation = "Excellent ";
            }

            if ($individuelle->formation->statut == "Terminée") {
                $individuelle->update([
                    "note_obtenue" => $value,
                    "appreciation" => $appreciation,
                    "statut"       => 'formés',
                ]);
            } else {
                Alert::warning('Désolé !', 'La formation n\'est pas encore achevée.');
                return redirect()->back();
            }

            $individuelle->save();

            $validated_by = new Validationindividuelle([
                'validated_id'     => Auth::user()->id,
                'action'           => 'formés',
                'individuelles_id' => $individuelle->id,
            ]);

            $validated_by->save();
        }

        Alert::success('Bravo !', 'L\'évaluation est terminée.');

        return redirect()->back();
    }

    public function givenotedemandeursCollective($idformation, Request $request)
    {
        $request->validate([
            'notes' => ['required'],
        ]);

        $listecollectives = $request->listecollectives;
        $notes            = $request->notes;

        $listecollectives_notes = array_combine($listecollectives, $notes);

        foreach ($listecollectives_notes as $key => $value) {
            $listecollective = Listecollective::findOrFail($key);
            if ($value <= 4) {
                $appreciation = "Médiocre";
            } elseif ($value <= 8) {
                $appreciation = "Insuffisant ";
            } elseif ($value <= 11) {
                $appreciation = "Passable ";
            } elseif ($value <= 13) {
                $appreciation = "Assez bien";
            } elseif ($value <= 16) {
                $appreciation = "Bien";
            } elseif ($value <= 19) {
                $appreciation = "Très bien";
            } elseif ($value = 20) {
                $appreciation = "Excellent ";
            }

            $listecollective->update([
                "note_obtenue" => $value,
                "appreciation" => $appreciation,
                "statut"       => 'formés',
            ]);

            $listecollective->save();

            $collectivemodule = $listecollective->collectivemodule;

            $collectivemodule->update([
                "statut" => 'formés',
            ]);

            $collectivemodule->save();

            $collective = $collectivemodule->collective;

            $collective->update([
                "statut_demande" => 'formés',
            ]);

            $collective->save();
        }

        /*  $validated_by = new Validationindividuelle([
        'validated_id'       =>      Auth::user()->id,
        'action'             =>      "Terminée",
        'listecollectives_id'   =>      $listecollective->id
        ]);

        $validated_by->save(); */

        Alert::success('Bravo !', 'L\'évaluation est terminée.');

        return redirect()->back();
    }

    public function updateAgentSuivi(Request $request)
    {
        $request->validate([
            'suivi_dossier' => ['required', 'string'],
            'date_suivi'    => ['required', 'date'],
        ]);

        $formation = Formation::findOrFail($request->input('id'));

        $formation->update([
            "suivi_dossier" => $request->input('suivi_dossier'),
            "date_suivi"    => $request->input('date_suivi'),
        ]);

        $formation->save();

        Alert::success('Réussi !', 'Enregistrement effectué avec succès.');

        return redirect()->back();
    }

    public function updateMembresJury(Request $request)
    {
        $request->validate([
            'membres_jury'      => ['nullable', 'string'],
            'evaluateur'        => ['required', 'string'],
            'numero_convention' => ['required', 'string'],
            'frais_evaluateur'  => ['required', 'string'],
            'type_certificat'   => ['nullable', 'string'],
            'recommandations'   => ['nullable', 'string'],
            'titre'             => ['nullable', 'string'],
            'date_pv'           => ['required', 'date', 'min:10', 'max:10', 'date_format:Y-m-d'],
            'date_convention'   => ['required', 'date', 'min:10', 'max:10', 'date_format:Y-m-d'],
        ]);

        $formation = Formation::findOrFail($request->input('id'));

        $referentiel = Referentiel::where('titre', $request->titre)->first();

        if (! empty($referentiel) && $request->titre != 'Renforcement de capacités') {
            $referentiel_id = $referentiel?->id;
            $titre          = null;
            $type           = 'Titre';
        } elseif ($request->titre == 'Renforcement de capacités') {
            $referentiel_id = null;
            $titre          = 'Renforcement de capacités';
            $type           = 'Attestation';
        } else {
            $referentiel_id = null;
            $titre          = null;
            $type           = null;
        }

        if (! empty($request->input('date_pv'))) {
            $date_pv = $request->input('date_pv');
        } else {
            $date_pv = null;
        }

        if (! empty($request->input('date_convention'))) {
            $date_convention = $request->input('date_convention');
        } else {
            $date_convention = null;
        }

        if (! empty($request->input('onfpevaluateur')) && $request->input('onfpevaluateur') === "Aucun") {
            $onfpevaluateur = null;
        } else {
            $onfpevaluateur = $request->input('onfpevaluateur');
        }

        $formation->update([
            "membres_jury"       => $request->input('membres_jury'),
            "numero_convention"  => $request->input('numero_convention'),
            "frais_evaluateur"   => $request->input('frais_evaluateur'),
            "type_certification" => $request->input('type_certification'),
            "type_certificat"    => $type,
            "titre"              => $titre,
            "recommandations"    => $request->input('recommandations'),
            "date_pv"            => $date_pv,
            "date_convention"    => $date_convention,
            "evaluateurs_id"     => $request->input('evaluateur'),
            "onfpevaluateurs_id" => $onfpevaluateur,
            "referentiels_id"    => $referentiel_id,
        ]);

        $formation->save();

        Alert::success('Réussi !', 'Enregistrement effectué avec succès.');

        return redirect()->back();
    }

    public function updateObservations(Request $request)
    {
        $request->validate([
            'observations' => 'required',
            'string',
        ]);

        $individuelle = Individuelle::findOrFail($request->input('id'));

        $individuelle->update([
            "observations" => $request->input('observations'),
        ]);

        $individuelle->save();

        Alert::success('Réussi !', 'Les observations ont été ajoutées.');

        return redirect()->back();
    }

    public function updateObservationsCollective(Request $request)
    {
        $request->validate([
            'observations' => 'required',
            'string',
        ]);

        $listecollective = Listecollective::findOrFail($request->input('id'));

        $listecollective->update([
            "observations" => $request->input('observations'),
        ]);

        $listecollective->save();

        Alert::success('Réussi !', 'Les observations ont été ajoutées.');

        return redirect()->back();
    }

    public function updateAttestations(Request $request)
    {
        $date_retrait = date_format(date_create($request->date_retrait), 'd/m/Y');

        $request->validate([
            'date_retrait' => 'required', 'date', 'min:10', 'max:10', 'date_format:Y-m-d',
            'personne'     => 'required',
        ]);

        if ($request->input('personne') == 'moi') {
            $retrait_diplome = 'le propriétaire le ' . $date_retrait;
        } else {
            $request->validate([
                'cin'          => 'required',
                'string',
                'max:15',
                'min:12',
                'name'         => 'required',
                'string',
                'observations' => 'nullable',
                'string',
                'max:50',
            ]);
            $retrait_diplome = 'retiré par ' . $request->input('personne') . ' le ' . $date_retrait . ' n° cin : ' . $request->input('cin');
        }

        $commentaires = $request->input('commentaires');

        if (isset($commentaires)) {
            $retrait_diplome = $retrait_diplome . '; ' . $commentaires;
        }

        $individuelle = Individuelle::findOrFail($request->input('id'));

        $individuelle->update([
            "retrait_diplome" => $retrait_diplome,
        ]);

        $individuelle->save();

        Alert::success('Merci et à bientôt !', 'Bonne continuation pour la suite.');

        return redirect()->back();
    }

    public function updateAttestationsCol(Request $request)
    {
        $date_retrait = date_format(date_create($request->date_retrait), 'd/m/Y');

        $request->validate([
            'date_retrait' => 'required', 'date', 'min:10', 'max:10', 'date_format:Y-m-d',
            'personne'     => 'required',
        ]);
        if ($request->input('personne') == 'moi') {
            $retrait_diplome = 'le propriétaire le ' . $date_retrait;
        } else {
            $request->validate([
                'cin'          => 'required',
                'string',
                'max:15',
                'min:12',
                'name'         => 'required',
                'string',
                'observations' => 'nullable',
                'string',
                'max:50',
            ]);
            $retrait_diplome = 'retiré par ' . $request->input('personne') . ' le ' . $date_retrait . ' n° cin : ' . $request->input('cin');
        }

        $commentaires = $request->input('commentaires');

        if (isset($commentaires)) {
            $retrait_diplome = $retrait_diplome . '; ' . $commentaires;
        }

        $listecollective = Listecollective::findOrFail($request->input('id'));

        $listecollective->update([
            "retrait_diplome" => $retrait_diplome,
        ]);

        $listecollective->save();

        Alert::success('Merci et à bientôt !', 'Bonne chance pour la suite');

        return redirect()->back();
    }

    public function ficheSuivi(Request $request)
    {

        $formation = Formation::find($request->input('id'));

        $title = 'Fiche de suivi de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.individuelles.fichesuivi', compact(
            'formation',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        /* $anne = date('d');
        $anne = $anne . ' ' . date('m');
        $anne = $anne . ' ' . date('Y');
        $anne = $anne . ' à ' . date('H') . 'h';
        $anne = $anne . ' ' . date('i') . 'min';
        $anne = $anne . ' ' . date('s') . 's'; */

        $name = 'Fiche de suivi de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function feuillePresence(Request $request)
    {

        $formation = Formation::find($request->input('id'));

        $title = 'Feuille de présence de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.individuelles.feuillepresence', compact(
            'formation',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        /* $anne = date('d');
        $anne = $anne . ' ' . date('m');
        $anne = $anne . ' ' . date('Y');
        $anne = $anne . ' à ' . date('H') . 'h';
        $anne = $anne . ' ' . date('i') . 'min';
        $anne = $anne . ' ' . date('s') . 's'; */

        $name = 'Feuille de présence de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function feuillePresenceCol(Request $request)
    {

        $formation = Formation::find($request->input('id'));

        $title = 'Feuille de présence de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.collectives.feuillepresence', compact(
            'formation',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        /* $anne = date('d');
        $anne = $anne . ' ' . date('m');
        $anne = $anne . ' ' . date('Y');
        $anne = $anne . ' à ' . date('H') . 'h';
        $anne = $anne . ' ' . date('i') . 'min';
        $anne = $anne . ' ' . date('s') . 's'; */

        $name = 'Feuille de présence de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function feuillePresenceJour(Request $request)
    {

        $formation = Formation::find($request->input('idformation'));
        /* $module     = Module::findOrFail($request->input('idmodule'));
        $region     = Region::findOrFail($request->input('idlocalite')); */
        $emargement = Emargement::findOrFail($request->input('idemargement'));

        /* if (! empty($formation?->projets_id)) {
            $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('individuelles.projets_id', $formation?->projets_id)
                ->where('individuelles.formations_id', $formation?->id)
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->get();
        } else {
            $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('individuelles.formations_id', $formation?->id)
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->get();
        } */

        $feuillepresenceIndividuelle = DB::table('feuillepresences')
            ->where('emargements_id', $emargement?->id)
            ->pluck('emargements_id', 'emargements_id')
            ->all();

        $title = 'Feuille de présence de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.individuelles.feuillepresencejour', compact(
            'formation',
            /* 'individuelles', */
            'emargement',
            'feuillepresenceIndividuelle',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        /* $anne = date('d');
        $anne = $anne . ' ' . date('m');
        $anne = $anne . ' ' . date('Y');
        $anne = $anne . ' à ' . date('H') . 'h';
        $anne = $anne . ' ' . date('i') . 'min';
        $anne = $anne . ' ' . date('s') . 's'; */

        $name = 'Feuille de présence de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function feuillePresenceColJour(Request $request)
    {

        $formation            = Formation::find($request->input('idformation'));
        $emargementcollective = Emargementcollective::findOrFail($request->input('idemargement'));

        $feuillepresenceListecollective = DB::table('feuillepresencecollectives')
            ->where('emargementcollectives_id', $emargementcollective?->id)
            ->pluck('emargementcollectives_id', 'emargementcollectives_id')
            ->all();

        $title = 'Feuille de présence de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.collectives.feuillepresencecoljour', compact(
            'formation',
            'emargementcollective',
            'feuillepresenceListecollective',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Feuille de présence de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function feuillePresenceTous(Request $request)
    {

        $formation  = Formation::find($request->input('idformation'));
        $emargement = Emargement::findOrFail($request->input('idemargement'));

        $feuillepresences = Feuillepresence::where('emargements_id', $request->idemargement)
            ->get();

        foreach ($feuillepresences as $key => $feuillepresence) {
            $feuillepresence->update([
                'presence' => "Oui",

            ]);

            $feuillepresence->save();
        }

        Alert::success("Modification réussie", "La modification a été effectuée avec succès.");

        return redirect()->back();

    }

    public function feuillePresenceColTous(Request $request)
    {

        $formation  = Formation::find($request->input('idformation'));
        $emargement = Emargementcollective::findOrFail($request->input('idemargement'));

        $feuillepresences = Feuillepresencecollective::where('emargementcollectives_id', $request->idemargement)
            ->get();

        foreach ($feuillepresences as $key => $feuillepresence) {
            $feuillepresence->update([
                'presence' => "Oui",

            ]);

            $feuillepresence->save();
        }

        Alert::success("Modification réussie", "La modification a été effectuée avec succès.");

        return redirect()->back();

    }

    public function feuillePresenceFinale(Request $request)
    {

        $formation = Formation::find($request->input('idformation'));
        /* $module    = Module::findOrFail($request->input('idmodule'));
        $region    = Region::findOrFail($request->input('idlocalite')); */

        /* if (! empty($formation?->projets_id)) {

            $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('individuelles.projets_id', $formation?->projets_id)
                ->where('individuelles.formations_id', $formation?->id)
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->get();
        } else {

            $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('individuelles.formations_id', $formation?->id)
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->get();
        } */

        $title = 'Feuille de présence de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.individuelles.feuillepresencefinale', compact(
            'formation',
            /* 'individuelles', */
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        /* $anne = date('d');
        $anne = $anne . ' ' . date('m');
        $anne = $anne . ' ' . date('Y');
        $anne = $anne . ' à ' . date('H') . 'h';
        $anne = $anne . ' ' . date('i') . 'min';
        $anne = $anne . ' ' . date('s') . 's'; */

        $name = 'Feuille de présence de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function feuillePresenceColFinale(Request $request)
    {

        $formation = Formation::find($request->input('idformation'));

        $title = 'Feuille de présence de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.collectives.feuillepresencecolfinale', compact(
            'formation',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Feuille de présence de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function etatTransport(Request $request)
    {

        $formation = Formation::find($request->input('idformation'));

        $title = 'Etat transport de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.individuelles.etatTransport', compact(
            'formation',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Etat transport de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function etatTransportCol(Request $request)
    {

        $formation = Formation::find($request->input('idformation'));

        $title = 'Etat transport de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.collectives.etatTransportCol', compact(
            'formation',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Etat transport de la formation en  ' . $formation?->collectivemodule?->module . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function pvEvaluation(Request $request)
    {

        $formation = Formation::find($request->input('id'));

        if ($formation->statut == "Terminée") {

            $title = 'PV Evaluation de la formation en  ' . $formation->name;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('Formation');
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view('formations.individuelles.pvevaluation', compact(
                'formation',
                'title',
                'membres_jury',
                'count_membres',
            )));

            // (Optional) Setup the paper size and orientation (portrait ou landscape)
            $dompdf->setPaper('A4', 'landscape');

            // Render the HTML as PDF
            $dompdf->render();

            /*  $anne = date('d');
            $anne = $anne . ' ' . date('m');
            $anne = $anne . ' ' . date('Y');
            $anne = $anne . ' à ' . date('H') . 'h';
            $anne = $anne . ' ' . date('i') . 'min';
            $anne = $anne . ' ' . date('s') . 's'; */

            $name = 'PV Evaluation de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

            // Output the generated PDF to Browser
            $dompdf->stream($name, ['Attachment' => false]);
        } else {
            Alert::warning('Désolé !', "La formation n'est pas encore terminée.");
            return redirect()->back();
        }
    }

    public function pvVierge(Request $request)
    {

        $formation = Formation::find($request->input('id'));

        if ($formation->statut == "Terminée") {

            $title = 'PV Evaluation de la formation en  ' . $formation->name;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('Formation');
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view('formations.individuelles.pvevaluation-vierge', compact(
                'formation',
                'title',
                'membres_jury',
                'count_membres',
            )));

            // (Optional) Setup the paper size and orientation (portrait ou landscape)
            $dompdf->setPaper('A4', 'landscape');

            // Render the HTML as PDF
            $dompdf->render();

            /*  $anne = date('d');
            $anne = $anne . ' ' . date('m');
            $anne = $anne . ' ' . date('Y');
            $anne = $anne . ' à ' . date('H') . 'h';
            $anne = $anne . ' ' . date('i') . 'min';
            $anne = $anne . ' ' . date('s') . 's'; */

            $name = 'PV Evaluation de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

            // Output the generated PDF to Browser
            $dompdf->stream($name, ['Attachment' => false]);
        } else {
            Alert::warning('Désolé !', "La formation n'est pas encore terminée.");
            return redirect()->back();
        }
    }

    public function ficheSuiviCol(Request $request)
    {

        $formation = Formation::find($request->input('id'));

        $title = 'Fiche de suivi de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.collectives.fichesuivicol', compact(
            'formation',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Fiche de suivi de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function pvEvaluationCol(Request $request)
    {

        $formation = Formation::find($request->input('id'));

        if ($formation->statut == "Terminée") {

            $title = 'PV Evaluation de la formation en  ' . $formation->name;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('Formation');
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view('formations.collectives.pvevaluationcol', compact(
                'formation',
                'title',
                'membres_jury',
                'count_membres',
            )));

            // (Optional) Setup the paper size and orientation (portrait ou landscape)
            $dompdf->setPaper('A4', 'landscape');

            // Render the HTML as PDF
            $dompdf->render();

            $name = 'PV Evaluation de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

            // Output the generated PDF to Browser
            $dompdf->stream($name, ['Attachment' => false]);
        } else {
            Alert::warning('Désolé !', "La formation n'est pas encore terminée.");
            return redirect()->back();
        }
    }

    public function pvViergeCol(Request $request)
    {

        $formation = Formation::find($request->input('id'));

        $title = 'PV Evaluation de la formation en  ' . $formation->name;

        $membres_jury  = explode(";", $formation->membres_jury);
        $count_membres = count($membres_jury);

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.collectives.pvevaluationcol-vierge', compact(
            'formation',
            'title',
            'membres_jury',
            'count_membres',
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'PV Evaluation de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);

    }

    public function addformationdemandeurscollectives($idformation, $idcollectivemodule, $idlocalite)
    {
        $formation        = Formation::findOrFail($idformation);
        $collectivemodule = Collectivemodule::findOrFail($idcollectivemodule);
        $localite         = Region::findOrFail($idlocalite);

        $listecollectives = Listecollective::join('collectives', 'collectives.id', 'listecollectives.collectives_id')
            ->select('listecollectives.*')
            ->where('collectives.id', $collectivemodule->collective->id)
            ->where('collectivemodules_id', $idcollectivemodule)
            ->get();

        $candidatsretenus = Listecollective::where('collectivemodules_id', $idcollectivemodule)
            ->where('formations_id', $idformation)
            ->get();

        $listecollectiveFormation = DB::table('listecollectives')
            ->where('formations_id', $idformation)
            ->pluck('formations_id', 'formations_id')
            ->all();

        return view("formations.collectives.add-listecollectives", compact('formation', 'listecollectives', 'listecollectiveFormation', 'collectivemodule', 'localite', 'candidatsretenus'));
    }

    public function giveformationdemandeurscollectives($idformation, $idcollectivemodule, $idlocalite, Request $request)
    {
        $request->validate([
            'listecollectives' => ['required'],
        ]);

        $formation = Formation::findOrFail($idformation);

        if ($formation->statut == "Terminée") {
            Alert::warning('Désolé !', 'Cette formation a déjà été exécutée.');
        } elseif ($formation->statut == 'Annulée') {
            Alert::warning('Désolé !', 'La formation a été annulée.');
        } else {
            $listecollectiveformations = Listecollective::where('formations_id', $idformation)->get();
            foreach ($listecollectiveformations as $key => $listecollectiveformation) {
                $listecollectiveformation->update([
                    "formations_id" => null,
                    "statut"        => 'Attente',
                ]);
                $listecollectiveformation->save();
            }

            foreach ($request->listecollectives as $listecollective) {
                $listecollective = Listecollective::findOrFail($listecollective);

                $listecollective->update([
                    "formations_id" => $idformation,
                    "statut"        => 'Retenue',
                ]);

                $listecollective->save();
            }

            /*  $validated_by = new Validationcollective([
            'validated_id'       =>      Auth::user()->id,
            'action'             =>      'Retenue',
            'collectives_id'   =>      $listecollective->id
            ]);

            $validated_by->save(); */

            Alert::success('Opération réussie !', 'Le(s) candidat(s) a/ont été ajouté(s) avec succès.');
        }

        return redirect()->back();
    }

    public function lettreEvaluation(Request $request)
    {

        $formation = Formation::find($request->input('id'));

        if ($formation->statut == "Terminée") {

            $title = 'Lettre de mission évaluation formation en  ' . $formation->name;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('Formation');
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view('formations.lettrevaluation', compact(
                'formation',
                'title',
                'membres_jury',
                'count_membres',
            )));

            // (Optional) Setup the paper size and orientation (portrait ou landscape)
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            /*  $anne = date('d');
            $anne = $anne . ' ' . date('m');
            $anne = $anne . ' ' . date('Y');
            $anne = $anne . ' à ' . date('H') . 'h';
            $anne = $anne . ' ' . date('i') . 'min';
            $anne = $anne . ' ' . date('s') . 's'; */

            $name = 'Lettre de mission évaluation formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

            // Output the generated PDF to Browser
            $dompdf->stream($name, ['Attachment' => false]);
        } else {
            Alert::warning('Désolé !', "La formation n'est pas encore terminée.");
            return redirect()->back();
        }
    }

    public function abeEvaluation(Request $request)
    {

        $formation = Formation::find($request->input('id'));

        $admis = Individuelle::where('formations_id', $formation->id)
            ->where('note_obtenue', '>=', '12')
            ->get();

        $recales = Individuelle::where('formations_id', $formation->id)
            ->where('note_obtenue', '<', '12')
            ->get();

        $admis_h_count = Individuelle::join('users', 'users.id', 'individuelles.users_id')
            ->select('individuelles.*')
            ->where('formations_id', $formation->id)
            ->where('users.civilite', "M.")
            ->where('note_obtenue', '>=', '12')
            ->count();

        $admis_f_count = Individuelle::join('users', 'users.id', 'individuelles.users_id')
            ->select('individuelles.*')
            ->where('formations_id', $formation->id)
            ->where('users.civilite', "Mme")
            ->where('note_obtenue', '>=', '12')
            ->count();

        $formes_h_count = Individuelle::join('users', 'users.id', 'individuelles.users_id')
            ->select('individuelles.*')
            ->where('formations_id', $formation->id)
            ->where('users.civilite', "M.")
            ->count();

        $formes_f_count = Individuelle::join('users', 'users.id', 'individuelles.users_id')
            ->select('individuelles.*')
            ->where('formations_id', $formation->id)
            ->where('users.civilite', "Mme")
            ->count();

        $formes_total = $formes_h_count + $formes_f_count;

        $retenus_h_count = Individuelle::join('users', 'users.id', 'individuelles.users_id')
            ->select('individuelles.*')
            ->where('formations_id', $formation->id)
            ->where('users.civilite', "M.")
            ->count();

        $retenus_f_count = Individuelle::join('users', 'users.id', 'individuelles.users_id')
            ->select('individuelles.*')
            ->where('formations_id', $formation->id)
            ->where('users.civilite', "Mme")
            ->count();

        $retenus_total = $retenus_h_count + $retenus_f_count;

        if ($formation->statut == "Terminée") {

            $title = 'Attestation de bonne execution ' . $formation->name;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('Formation');
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view('formations.abe', compact(
                'formation',
                'title',
                'membres_jury',
                'count_membres',
                'admis',
                'recales',
                'admis_h_count',
                'admis_f_count',
                'formes_h_count',
                'formes_f_count',
                'formes_total',
                'retenus_h_count',
                'retenus_f_count',
                'retenus_total',
            )));

            // (Optional) Setup the paper size and orientation (portrait ou landscape)
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            /*  $anne = date('d');
            $anne = $anne . ' ' . date('m');
            $anne = $anne . ' ' . date('Y');
            $anne = $anne . ' à ' . date('H') . 'h';
            $anne = $anne . ' ' . date('i') . 'min';
            $anne = $anne . ' ' . date('s') . 's'; */

            $name = 'Attestation de bonne execution ' . $formation->name . ', code ' . $formation->code . '.pdf';

            // Output the generated PDF to Browser
            $dompdf->stream($name, ['Attachment' => false]);
        } else {
            Alert::warning('Désolé !', "La formation n'est pas encore terminée.");
            return redirect()->back();
        }
    }

    public function rapports(Request $request)
    {
        $title = 'rapports formations';
        return view('formations.rapports', compact(
            'title'
        ));
    }
    public function generateRapport(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required|date',
            'to_date'   => 'required|date',
        ]);

        $now = Carbon::now()->format('H:i:s');

        $from_date = date_format(date_create($request->from_date), 'd/m/Y');

        $to_date = date_format(date_create($request->to_date), 'd/m/Y');

        $formations = Formation::whereBetween(DB::raw('DATE(date_debut)'), [$request->from_date, $request->to_date])->get();

        $count = $formations->count();

        if ($from_date == $to_date) {
            if (isset($count) && $count < "1") {
                $title = 'aucune formation effctuée le ' . $from_date;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' formation effctuée le ' . $from_date;
            } else {
                $title = $count . ' formations effctuées le ' . $from_date;
            }
        } else {
            if (isset($count) && $count < "1") {
                $title = 'aucune formation effctuée entre le ' . $from_date . ' et le ' . $to_date;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' formation effctuée entre le ' . $from_date . ' et le ' . $to_date;
            } else {
                $title = $count . ' formations effctuées entre le ' . $from_date . ' et le ' . $to_date;
            }
        }

        return view('formations.rapports', compact(
            'formations',
            'from_date',
            'to_date',
            'title'
        ));
    }

    public function rapportsformes(Request $request)
    {
        $regions = Region::get();
        $projets = Projet::get();
        $title   = 'rapports formés individuelles';

        return view('formes.rapports', compact(
            'regions',
            'projets',
            'title'
        ));
    }

    public function formesCollective(Request $request)
    {
        $regions = Region::get();
        $projets = Projet::get();
        $title   = 'rapports formés collectives';

        return view('formes.rapport-collective', compact(
            'regions',
            'projets',
            'title'
        ));
    }
    public function generateRapportFormes(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required|date',
            'to_date'   => 'required|date',
        ]);

        $now = Carbon::now()->format('H:i:s');

        $from_date = date_format(date_create($request->from_date), 'd/m/Y');

        $to_date = date_format(date_create($request->to_date), 'd/m/Y');

        if (! empty($request->module) && ! empty($request->region) && ! empty($request->projet)) {
            $module = Module::where('name', $request->module)->first();
            $region = Region::where('nom', $request->region)->first();
            $projet = Projet::where('name', $request->projet)->first();

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formés')
                ->where('individuelles.modules_id', 'LIKE', "%{$module?->id}%")
                ->where('individuelles.regions_id', $region?->id)
                ->where('individuelles.projets_id', $projet?->id)
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();

        } elseif (! empty($request->region)) {
            $region = Region::where('nom', $request->region)->first();

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formés')
                ->where('individuelles.regions_id', $region?->id)
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } elseif (! empty($request->projet)) {
            $projet = Projet::where('sigle', $request->projet)->first();

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formés')
                ->where('individuelles.projets_id', $projet?->id)
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } elseif (! empty($request->module)) {
            $module = Module::where('name', $request->module)->first();

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formés')
                ->where('individuelles.modules_id', 'LIKE', "%{$module?->id}%")
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } else {
            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formés')
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        }

        $count = $formes->count();

        if ($from_date == $to_date) {
            if (! empty($count) && $count < "1") {
                $title = 'aucun bénéficiaire formé le ' . $from_date;
            } elseif (! empty($count) && $count == "1") {
                $title = $count . ' bénéficiaire formé le ' . $from_date;
            } else {
                $title = $count . ' bénéficiaires formé le ' . $from_date;
            }
        } else {
            if (! empty($count) && $count < "1") {
                $title = 'aucun bénéficiaire formé dans la période du ' . $from_date . ' au ' . $to_date;
            } elseif (! empty($count) && $count == "1") {
                $title = $count . ' bénéficiaire formé dans la période du ' . $from_date . ' au ' . $to_date;
            } else {
                $title = $count . ' bénéficiaires formés dans la période du ' . $from_date . ' au ' . $to_date;
            }
        }

        $regions = Region::get();
        $projets = Projet::get();

        if ($request->module) {
            $title = $request->module . ' : ' . $title;
        } else {
            $title = $title;
        }

        return view('formes.rapports', compact(
            'formes',
            'regions',
            'projets',
            'title'
        ));
    }
    public function generateRapportFormesCollective(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required|date',
            'to_date'   => 'required|date',
        ]);

        $now = Carbon::now()->format('H:i:s');

        $from_date = date_format(date_create($request->from_date), 'd/m/Y');

        $to_date = date_format(date_create($request->to_date), 'd/m/Y');

        if (isset($request->module)) {
            $module = Collectivemodule::where('module', $request->module)->first();
            $formes = Listecollective::join('formations', 'formations.id', 'listecollectives.formations_id')
                ->select('listecollectives.*')
                ->where('listecollectives.statut', 'formés')
                ->where('listecollectives.collectivemodules_id', 'LIKE', "%{$module?->id}%")
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } else {
            $formes = Listecollective::join('formations', 'formations.id', 'listecollectives.formations_id')
                ->select('listecollectives.*')
                ->where('listecollectives.statut', 'formés')
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        }

        $count = $formes->count();

        if ($from_date == $to_date) {
            if (isset($count) && $count < "1") {
                $title = 'aucun bénéficiaire formé le ' . $from_date;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' bénéficiaire formé le ' . $from_date;
            } else {
                $title = $count . ' bénéficiaires formé le ' . $from_date;
            }
        } else {
            if (isset($count) && $count < "1") {
                $title = 'aucun bénéficiaire formé dans la période ' . $from_date . ' au ' . $to_date;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' bénéficiaire formé dans la période ' . $from_date . ' au ' . $to_date;
            } else {
                $title = $count . ' bénéficiaires formés dans la période ' . $from_date . ' au ' . $to_date;
            }
        }

        $regions = Region::get();
        $projets = Projet::get();

        return view('formes.rapport-collective', compact(
            'formes',
            'regions',
            'projets',
            'title'
        ));
    }

    public function suiviformes(Request $request)
    {
        $regions = Region::get();
        $title   = 'Base de données des formés individuels suivis';

        $formes = Individuelle::where('suivi', 'suivi')->get();

        return view('formes.suivi-individuelle', compact(
            'regions',
            'formes',
            'title'
        ));
    }

    public function suiviformesCol(Request $request)
    {
        $regions = Region::get();
        $title   = 'Base de données des formés collectifs suivis';

        $formes = Listecollective::where('suivi', 'suivi')->get();

        return view('formes.suivi-collective', compact(
            'regions',
            'formes',
            'title'
        ));
    }
    public function generateSuiviFormes(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required|date',
            'to_date'   => 'required|date',
        ]);

        $now = Carbon::now()->format('H:i:s');

        $from_date = date_format(date_create($request->from_date), 'd/m/Y');

        $to_date = date_format(date_create($request->to_date), 'd/m/Y');

        if (isset($request->module) && isset($request->region)) {
            $module              = Module::where('name', $request->module)->first();
            $region              = Region::where('nom', $request->region)->first();
            $title_region_module = ' dans la région de ' . $request->region . ' en ' . $request->module;

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formés')
                ->where('individuelles.modules_id', 'LIKE', "%{$module?->id}%")
                ->where('individuelles.regions_id', $region?->id)
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } elseif (isset($request->region)) {
            $region              = Region::where('nom', $request->region)->first();
            $title_region_module = ' dans la région de ' . $request->region;

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formés')
                ->where('individuelles.regions_id', $region?->id)
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } elseif (isset($request->module)) {
            $module              = Module::where('name', $request->module)->first();
            $title_region_module = ' en ' . $request->module;

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formés')
                ->where('individuelles.modules_id', 'LIKE', "%{$module?->id}%")
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } else {
            $title_region_module = '';
            $formes              = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formés')
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        }

        $count = $formes->count();

        if ($from_date == $to_date) {
            if (isset($count) && $count < "1") {
                $title = 'aucun bénéficiaire formé le ' . $from_date . ' ' . $title_region_module;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' bénéficiaire formé le ' . $from_date . ' ' . $title_region_module;
            } else {
                $title = $count . ' bénéficiaires formé le ' . $from_date . ' ' . $title_region_module;
            }
        } else {
            if (isset($count) && $count < "1") {
                $title = 'aucun bénéficiaire formé entre le ' . $from_date . ' et le ' . $to_date . ' ' . $title_region_module;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' bénéficiaire formé entre le ' . $from_date . ' et le ' . $to_date . ' ' . $title_region_module;
            } else {
                $title = $count . ' bénéficiaires formés entre le ' . $from_date . ' et le ' . $to_date . ' ' . $title_region_module;
            }
        }

        $regions = Region::get();
        return view('formes.suivi', compact(
            'formes',
            'regions',
            'title'
        ));
    }

    public function SuivreFormes(Request $request, $id)
    {
        $individuelle = Individuelle::findOrFail($id);

        $individuelle->update([
            'suivi' => 'suivi',
        ]);

        $individuelle->save();

        Alert::success('Demandeur suivi avec succès !');

        return redirect()->back();
    }

    public function nepasSuivre(Request $request, $id)
    {
        $individuelle = Individuelle::findOrFail($id);

        $individuelle->update([
            'suivi' => null,
        ]);

        $individuelle->save();

        Alert::success('Merci !', 'L’arrêt du suivi du demandeur a été effectué avec succès !');

        return redirect()->back();
    }

    public function suivreTous(Request $request, $id)
    {
        $formation = Formation::findOrFail($id);
        foreach ($formation->individuelles as $individuelle) {
            $individuelle->update([
                'suivi' => 'suivi',
            ]);

            $individuelle->save();
        }

        Alert::success('Merci !', 'Demandeurs suivis avec succès !');

        return redirect()->back();
    }

    public function FormeSuivi(Request $request)
    {
        $this->validate($request, [
            'informations_suivi' => 'required|string',
        ]);

        $individuelle = Individuelle::findOrFail($request->id);

        $individuelle->update([
            'informations_suivi' => $request->informations_suivi,
        ]);

        $individuelle->save();

        Alert::success('Enregistrement réussi !');

        return redirect()->back();
    }

    public function SuivreFormesCol(Request $request, $id)
    {
        $listecollective = Listecollective::findOrFail($id);

        $listecollective->update([
            'suivi' => 'suivi',
        ]);

        $listecollective->save();

        Alert::success('Merci !', 'Demandeur suivi avec succès !');

        return redirect()->back();
    }
    public function suivretousCol(Request $request, $id)
    {
        $formation = Formation::findOrFail($id);

        foreach ($formation->listecollectives as $listecollective) {
            $listecollective->update([
                'suivi' => 'suivi',
            ]);

            $listecollective->save();
        }

        Alert::success('Merci !', 'Demandeur suivi avec succès !');

        return redirect()->back();
    }

    public function nepasSuivreCol(Request $request, $id)
    {
        $listecollective = Listecollective::findOrFail($id);

        $listecollective->update([
            'suivi' => null,
        ]);

        $listecollective->save();

        Alert::success('Merci !', 'L’arrêt du suivi du demandeur a été effectué avec succès !');

        return redirect()->back();
    }

    public function FormeColSuivi(Request $request)
    {
        $this->validate($request, [
            'informations_suivi' => 'required|string',
        ]);

        $individuelle = Listecollective::findOrFail($request->id);

        $individuelle->update([
            'informations_suivi' => $request->informations_suivi,
        ]);

        $individuelle->save();

        Alert::success('Enregistrement réussi !');

        return redirect()->back();
    }

    public function generateReport(Request $request)
    {
        $this->validate($request, [
            'annee'  => 'required|numeric',
            'statut' => 'required|string',
        ]);

        if ($request->statut == 'Tous') {
            $formations = Formation::where('annee', $request->annee)
                ->get();
        } else {
            $formations = Formation::where('annee', $request->annee)
                ->where('statut', $request->statut)
                ->get();
        }

        $title = 'SUIVI CONVENTIONS  ' . $request->annee;

        return view('formations.reports', compact(
            'formations',
            'title'
        ));
    }

    public function showConventions()
    {
        $conventions = Formation::where('numero_convention', '!=', null)->get();

        return view('formations.convention', compact('conventions'));
    }

    public function ajouterJours(Request $request)
    {

        $this->validate($request, [
            'jour' => "required|numeric",
            /* 'date' => 'nullable|date|min:10|max:10|date_format:Y-m-d', */
        ]);

        $formation = Formation::findOrFail($request->idformation);

        if (! empty($formation->duree_formation)) {

            if (count($formation->individuelles) <= 0) {

                Alert::warning('Impossible !', 'Aucun bénéficiaire dans cette formation');

                return redirect()->back();
            }

            $nbre_jours = $request->jour;

            $emargement_count = Emargement::where('formations_id', $request->idformation)->count();

            if ($emargement_count < $formation->duree_formation) {
                if (! empty($emargement_count)) {
                    $nbre_jours       = $nbre_jours + $emargement_count + 1;
                    $emargement_count = $emargement_count + 1;
                } else {
                    $emargement_count = 1;
                    $nbre_jours       = $nbre_jours + $emargement_count;
                }

                $i = $emargement_count;

                for ($i = $emargement_count; $i < $nbre_jours; $i++) {
                    $emargement = Emargement::create([
                        'jour'          => 'Jour ' . $i,
                        'formations_id' => $request->idformation,

                    ]);

                    foreach ($formation->individuelles as $key => $individuelle) {
                        $feuillepresence = Feuillepresence::create([
                            'emargements_id'   => $emargement->id,
                            'individuelles_id' => $individuelle->id,
                            'presence'         => null,
                        ]);
                    }
                }

                Alert::success('Enregistrement réussi !');

            } else {
                Alert::warning('Attention !', 'Vous avez atteint le nombre maximum de feuilles de présence à créer, car elles ne peuvent pas dépasser le nombre de jours de formation.');
            }

        } else {
            Alert::warning('Attention !', 'renseignez d\'abord la durée (nombre de jours) de formation');
        }

        return redirect()->back();
    }

    public function ajouterJoursCol(Request $request)
    {
        $this->validate($request, [
            'jour' => "required|numeric",
            /* 'date' => 'nullable|date|min:10|max:10|date_format:Y-m-d', */
        ]);

        $formation = Formation::findOrFail($request->idformation);

        if (! empty($formation->duree_formation)) {

            if (count($formation->listecollectives) <= 0) {

                Alert::warning('Impossible !', 'Aucun bénéficiaire dans cette formation');

                return redirect()->back();
            }

            $nbre_jours = $request->jour;

            $emargement_count = Emargementcollective::where('formations_id', $request->idformation)->count();

            if ($emargement_count < $formation->duree_formation) {
                if (! empty($emargement_count)) {
                    $nbre_jours       = $nbre_jours + $emargement_count + 1;
                    $emargement_count = $emargement_count + 1;
                } else {
                    $emargement_count = 1;
                    $nbre_jours       = $nbre_jours + $emargement_count;
                }

                $i = $emargement_count;

                for ($i = $emargement_count; $i < $nbre_jours; $i++) {
                    $emargement = Emargementcollective::create([
                        'jour'          => 'Jour ' . $i,
                        'formations_id' => $request->idformation,

                    ]);

                    foreach ($formation->listecollectives as $key => $listecollective) {
                        $feuillepresence = Feuillepresencecollective::create([
                            'emargementcollectives_id' => $emargement->id,
                            'listecollectives_id'      => $listecollective->id,
                            'presence'                 => null,
                        ]);
                    }
                }
                Alert::success('Enregistrement réussi !');

            } else {
                Alert::warning('Attention !', 'Vous avez atteint le nombre maximum de feuilles de présence à créer, car elles ne peuvent pas dépasser le nombre de jours de formation.');
            }
        } else {
            Alert::warning('Attention !', 'renseignez d\'abord la durée (nombre de jours) de formation');
        }

        return redirect()->back();
    }

    public function sendTrainingStartEmail(Request $reques, $trainingId)
    {
        $formation = Formation::findOrFail($trainingId);

        foreach ($formation?->individuelles as $key => $individuelle) {
            // Exécuter la commande Artisan pour envoyer les e-mails
            Artisan::call('email:notify-training-start', [
                'formations_id' => $formation->id, // Passer l'ID de la formation
            ]);
        }

        Alert::success('Les e-mails ont été envoyés avec succès !');

        return redirect()->back();
    }
}
