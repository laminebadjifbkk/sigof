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
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use NumberToWords\NumberToWords;
use RealRashid\SweetAlert\Facades\Alert;

class FormationController extends Controller
{

    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DIOF|ADIOF|Ingenieur|DEC|Operateur|Employe']);
        $this->middleware("permission:formation-view", ["only" => ["index"]]);
        /* $this->middleware(['permission:arrive-show']); */
        // or with specific guard
        /* $this->middleware(['role_or_permission:super-admin']); */
    }

    public function index()
    {
        /* $formations = Formation::where('statut', '!=', 'supprimer')->orderBy('created_at', 'desc')->get(); */
        $formations = Formation::select('*')->orderBy('created_at', 'desc')->get();

        $groupes = $formations->groupBy(function ($item) {
            return $item->types_formation->name ?? 'Aucun type';
        });

        /* $individuelles_formations_count = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
            ->select('formations.*')
            ->where('types_formations.name', "individuelle")
            ->where('statut', '!=', 'supprimer')
            ->count();

        $collectives_formations_count = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
            ->select('formations.*')
            ->where('types_formations.name', "collective")
            ->where('statut', '!=', 'supprimer')
            ->count(); */

        /* $qrcode      = QrCode::size(200)->generate("tel:+221776994173"); */
        $today       = date('Y-m-d');
        $count_today = Formation::where("created_at", "LIKE", "{$today}%")->count();

        $modules      = Module::orderBy("created_at", "desc")->get();
        $departements = Departement::orderBy("created_at", "desc")->get();
        $regions      = Region::orderBy("created_at", "desc")->get();
        $operateurs   = Operateur::orderBy("created_at", "desc")->get();
        $projets      = Projet::orderBy("created_at", "desc")->get();
        $programmes   = Programme::orderBy("created_at", "desc")->get();
        /* $choixoperateurs  = Choixoperateur::orderBy("created_at", "desc")->get(); */
        $types_formations = TypesFormation::orderBy("created_at", "desc")->get();

        $anneeEnCours = date('Y');
        $an           = date('y');

        /* $numFormation = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
            ->select('formations.*')
            ->where('formations.annee', $anneeEnCours)
            ->get(); */

        // Si tu veux le dernier enregistrement selon la colonne id (le plus grand id)
        $numFormation = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
            ->select('formations.*')
            ->where('formations.annee', $anneeEnCours)
            ->orderBy('formations.id', 'desc')
            ->first();

        // Si tu veux le dernier enregistrement selon created_at
        $numFormation = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
            ->select('formations.*')
            ->where('formations.annee', $anneeEnCours)
            ->latest('formations.created_at')
            ->first();

        /*  if (isset($numFormation)) {
            $numFormation = Formation::join('types_formations', 'types_formations.id', 'formations.types_formations_id')
                ->select('formations.*')
                ->where('formations.annee', $anneeEnCours)
                ->get()->last()->code;

            $numFormation = ++$numFormation;

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
        } */

        if ($numFormation) {
            // Si un formation existe, incrémenter son numéro
            $numFormation = ++$numFormation->code;
        } else {
            // Si aucun formation n'existe, initialiser avec l'année et le numéro 0001
            $numFormation = $an . "0001";
            $numFormation = 'F' . $numFormation;
        }

// Mise en forme du numéro de formation en ajoutant des zéros au début
        /* $numFormation = str_pad($numFormation, 7, '0', STR_PAD_LEFT); */

        /* dd($numFormation); */

        /* $title = 'Liste des formations de ' . $anneeEnCours; */
        $title = 'Liste des formations';

        $formations_annee = Formation::distinct()
            ->get('annee');

        $formations_statut = Formation::distinct()
            ->get('statut');

        return view(
            "formations.index",
            compact(
                /* "qrcode", */
                "count_today",
                /* "collectives_formations_count",
                "individuelles_formations_count", */
                "formations",
                "modules",
                "departements",
                "regions",
                "operateurs",
                'types_formations',
                'projets',
                'programmes',
                'numFormation',
                /* 'choixoperateurs', */
                'title',
                'formations_annee',
                'formations_statut',
                'groupes',
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
            "name"               => "required|string",
            "departement"        => "required|string",
            "lieu"               => "required|string",
            "type_certification" => "required|string",
            "types_formation"    => "required|string",
            "date_debut"         => "nullable|date|size:10|date_format:Y-m-d",
            "date_fin"           => "nullable|date|size:10|date_format:Y-m-d",
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

    public function edit(Formation $formation)
    {
        $departements     = Departement::orderBy("created_at", "desc")->get();
        $types_formations = TypesFormation::orderBy("created_at", "desc")->get();
        $projets          = Projet::orderBy("created_at", "desc")->get();
        $programmes       = Programme::orderBy("created_at", "desc")->get();
        $choixoperateurs  = Choixoperateur::orderBy("created_at", "desc")->get();
        $referentiels     = Referentiel::get();

        $evaluateurs     = Evaluateur::get();
        $onfpevaluateurs = Onfpevaluateur::get();

        $formationEvaluateurs     = $formation->evaluateurs->pluck('name', 'lastname')->all();
        $formationOnfpevaluateurs = $formation->onfpevaluateurs->pluck('name', 'lastname')->all();

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
                'formationEvaluateurs',
                'formationOnfpevaluateurs',
            )
        );
    }

    public function update(Request $request, Formation $formation)
    {
        $this->validate($request, [
            "code"               => "required|string|unique:formations,code,{$formation->id}",
            /* "name"               => "required|string|unique:formations,name,{$formation->id}", */
            "name"               => "required|string",
            "departement"        => "required|string",
            "lieu"               => "required|string",
            "type_certification" => "required|string",
            "titre"              => "nullable|string",
            "date_debut"         => "nullable|date|size:10|date_format:Y-m-d",
            "date_convention"    => "nullable|date|size:10|date_format:Y-m-d",
            "date_lettre"        => "nullable|date|size:10|date_format:Y-m-d",
            "date_lettre_dec"    => "nullable|date|size:10|date_format:Y-m-d",
            "date_fin"           => "nullable|date|size:10|date_format:Y-m-d",
            "lettre_mission"     => "nullable|string",
            "annee"              => "nullable|numeric",
            "file_convention"    => ['sometimes', 'file', 'mimes:pdf', 'max:1024'],
            "detf-file"          => ['sometimes', 'file', 'mimes:pdf', 'max:1024'],
        ]);

        // Simplification des champs simples
        $prevue_h = (int) $request->input('prevue_h', 0);
        $prevue_f = (int) $request->input('prevue_f', 0);

        $effectif_prevu = $prevue_h + $prevue_f;

// Chargement projet et référentiel
        $projet      = Projet::where('sigle', $request->input('projet'))->first();
        $referentiel = Referentiel::where('titre', $request->titre)->first();

// Détermination du type et du titre
        if (! empty($referentiel) && $request->titre !== 'Renforcement de capacités') {
            $referentiel_id = $referentiel->id;
            $titre          = null;
            $type           = 'Titre';
        } elseif ($request->titre === 'Renforcement de capacités') {
            $referentiel_id = null;
            $titre          = 'Renforcement de capacités';
            $type           = 'Attestation';
        } else {
            $referentiel_id = null;
            $titre          = null;
            $type           = null;
        }

// Fonction pour gérer les fichiers
        function handleFileUpload($requestKey, $storagePath, $formation, $fieldName)
        {
            if (request()->hasFile($requestKey)) {
                if (! empty($formation->$fieldName)) {
                    Storage::disk('public')->delete($formation->$fieldName);
                }
                $filePath = request()->file($requestKey)->store($storagePath, 'public');
                $formation->update([
                    $fieldName => $filePath,
                ]);
            }
        }

// Traitement des fichiers
        handleFileUpload('file_convention', 'conventions', $formation, 'file_convention');
        handleFileUpload('detf_file', 'detfs', $formation, 'detf_file');
        handleFileUpload('file_pv', 'pvs', $formation, 'file_pv');
        handleFileUpload('abe_file', 'abe', $formation, 'abe_file');
        handleFileUpload('lettre_mission_file', 'lm', $formation, 'lettre_mission_file');

        // Fonction utilitaire pour parser une date ou retourner null
        function parseDateOrNull($value)
        {
            return ! empty($value) ? date('Y-m-d H:i:s', strtotime($value)) : null;
        }

// Dates
        $date_debut  = parseDateOrNull($request->input('date_debut'));
        $date_fin    = parseDateOrNull($request->input('date_fin'));
        $date_pv     = parseDateOrNull($request->input('date_pv'));
        $date_lettre = parseDateOrNull($request->input('date_lettre'));
        /* $date_lettre_dec = parseDateOrNull($request->input('date_lettre_dec')); */
        $date_convention = parseDateOrNull($request->input('date_convention'));
        $date_etat       = parseDateOrNull($request->input('date_etat'));

// Champs simples (valeur ou null)
        $frais_operateurs = $request->input('frais_operateurs') ?: null;
        $frais_add        = $request->input('frais_add') ?: null;
        $autes_frais      = $request->input('autes_frais') ?: null;
        $frais_evaluateur = $request->input('frais_evaluateur') ?: null;
        /* $onfpevaluateur           = $request->input('onfpevaluateur') ?: null; */
        $duree_formation          = $request->input('duree_formation') ?: null;
        $indemnite_transport_jour = $request->input('indemnite_transport_jour') ?: null;

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
            /* "date_lettre_dec"          => $date_lettre_dec, */
            "effectif_prevu"           => $effectif_prevu,
            "prevue_h"                 => $prevue_h,
            "prevue_f"                 => $prevue_f,
            "frais_operateurs"         => $frais_operateurs,
            "frais_add"                => $frais_add,
            "autes_frais"              => $autes_frais,
            "projets_id"               => $projet?->id,
            "lettre_mission"           => $request->input('lettre_mission'),
            /* "lettre_mission_dec"       => $request->input('lettre_mission_dec'), */
            "programmes_id"            => $request->input('programme'),
            "choixoperateurs_id"       => $request->input('choixoperateur'),
            "referentiels_id"          => $referentiel_id,
            "annee"                    => $request->input('annee'),
            "membres_jury"             => $request->input('membres_jury'),
            "frais_evaluateur"         => $frais_evaluateur,
            "recommandations"          => $request->input('recommandations'),
            "date_pv"                  => $date_pv,
            /* "evaluateurs_id"        =>   $request->input('evaluateur'), */
            /* "onfpevaluateurs_id"       => $onfpevaluateur, */
            "attestation"              => $request->statut,
            "duree_formation"          => $duree_formation,
            "date_etat"                => $date_etat,
            "indemnite_transport_jour" => $indemnite_transport_jour,

        ]);

        $formation->save();

        $formation->evaluateurs()->sync($request->evaluateur);
        /* $formation->onfpevaluateurs()->sync($request->onfpevaluateur); */

        Alert::success("Succès !", "Modification effectuée avec succès.");

        return redirect()->back();
    }

    public function show(Formation $formation)
    {
        /* $formation         = Formation::findOrFail($id);
        $type_formation    = $formation?->types_formation->name;
        $operateur         = $formation?->operateur;
        $module            = $formation?->module;
        $module_collective = $formation?->collectivemodule;
        $ingenieur         = $formation?->ingenieur;
        $emargements           = $formation->emargements;
        $emargementcollectives = $formation->emargementcollectives;

        $count_demandes = count($formation->individuelles);
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
            ->all(); */

        /* $formation = Formation::with([
            'types_formation:id,name',
            'operateur',
            'module',
            'collectivemodule',
            'ingenieur',
            'emargements',
            'emargementcollectives',
            'individuelles',
        ])->findOrFail($id); */

        $type_formation    = $formation->types_formation?->name;
        $operateur         = $formation->operateur;
        $module            = $formation->module;
        $module_collective = $formation->collectivemodule;
        $ingenieur         = $formation->ingenieur;
        $emargements       = $formation->emargements;
        /* $emargementcollectives = $formation->emargementcollectives; */
        $emargementcollectives = Emargementcollective::where('formations_id', $formation->id)->get();
        $count_demandes        = $formation->individuelles->count();

// Chargement en batch des données secondaires
        $listecollectives = Listecollective::latest()->get();
        $evaluateurs      = Evaluateur::latest()->get();
        $onfpevaluateurs  = Onfpevaluateur::latest()->get();
        $referentiels     = Referentiel::all();

// collectives_id est déjà chargé via $formation
        $collectivemodule = Collectivemodule::where('collectives_id', $formation->collectives_id)->get();

        $collectiveFormation = Formation::where('collectivemodules_id', $formation->collectivemodules_id)
            ->pluck('collectivemodules_id')
            ->all();

        /*  $collectivemodules = Collectivemodule::join('collectives', 'collectives.id', '=', 'collectivemodules.collectives_id')
            ->select('collectivemodules.*')
            ->where('collectives.statut_demande', 'Attente')
            ->orWhereIn('collectivemodules.statut', ['Retenu', 'Retiré', 'formés'])
            ->get(); */

        $statutsVoulus = ['attente', 'conforme'];

        $collectivemodules = Collectivemodule::join('collectives', 'collectives.id', '=', 'collectivemodules.collectives_id')
            ->select('collectivemodules.*')
            ->whereIn('collectivemodules.statut', $statutsVoulus)
            ->get();

        $collectiveModule = Collectivemodule::where('formations_id', $formation->id)
            ->pluck('formations_id')
            ->all();

        $collectiveModuleCheck = Collectivemodule::whereNotNull('formations_id')
            ->where('formations_id', '!=', $formation->id)
            ->pluck('formations_id')
            ->all();

        return view(
            'formations.' . $type_formation . "s.show",
            compact(
                "evaluateurs",
                "onfpevaluateurs",
                "formation",
                "count_demandes",
                "operateur",
                "module",
                "module_collective",
                "type_formation",
                "listecollectives",
                "collectiveFormation",
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

    public function destroy(Formation $formation)
    {
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

                if (! empty($formation->file_lettre_dec)) {
                    Storage::disk('public')->delete($formation->file_lettre_dec);
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

                if (! empty($formation->file_lettre_dec)) {
                    Storage::disk('public')->delete($formation->file_lettre_dec);
                }

                if (! empty($formation->abe_file)) {
                    Storage::disk('public')->delete($formation->abe_file);
                }

                $formation->delete();

                Alert::success('Succès !', 'La formation a été supprimée avec succès.');

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

            Alert::success('Succès !', 'La formation a été supprimée avec succès.');

            return redirect()->back();
        }
    }

    public function addformationdemandeurs($idformation, $idmodule, $idlocalite)
    {
        $formation = Formation::findOrFail($idformation);
        $module    = Module::findOrFail($idmodule);
        $region    = Region::findOrFail($idlocalite);

        if (! empty($formation?->projets_id)) {
            /* $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('individuelles.projets_id', $formation?->projets_id)
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->where('individuelles.statut', 'Attente')
                ->get(); */
            $statutsVoulus = ['attente', 'conforme', 'retirée', 'retiré', 'liste attente', 'Sélectionné'];

            $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('individuelles.projets_id', $formation?->projets_id)
                ->where('modules.name', 'LIKE', '%' . $module->name . '%')
                ->where('regions.nom', $region->nom)
                ->whereIn('individuelles.statut', $statutsVoulus)
                ->orderBy('individuelles.note', 'desc') // tri par note décroissante
                ->get();

            /* $retirer_individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('individuelles.projets_id', $formation?->projets_id)
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->where('individuelles.statut', 'Retiré')
                ->get(); */
        } else {
            /* $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->where('individuelles.statut', 'Attente')
                ->get(); */

            $statutsVoulus = ['attente', 'conforme', 'retirée', 'retiré', 'liste attente', 'Sélectionné'];

            $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
            /* ->where('individuelles.projets_id', $formation?->projets_id) */
                ->where('modules.name', 'LIKE', '%' . $module->name . '%')
                ->where('regions.nom', $region->nom)
                ->whereIn('individuelles.statut', $statutsVoulus)
                ->orderBy('individuelles.note', 'desc') // tri par note décroissante
                ->get();

            /* $retirer_individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
                ->join('regions', 'regions.id', 'individuelles.regions_id')
                ->select('individuelles.*')
                ->where('modules.name', 'LIKE', "%{$module->name}%")
                ->where('regions.nom', $region->nom)
                ->where('individuelles.statut', 'Retiré')
                ->get(); */
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
                /*'retirer_individuelles', */
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
                    "statut"        => 'Sélectionné',
                ]);

                $individuelle->save();
            }

            $validated_by = new Validationindividuelle([
                'validated_id'     => Auth::user()->id,
                'action'           => 'Sélectionné',
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
                "statut"        => 'Retiré',
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
                'action'           => 'Retiré',
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
                "statut"        => 'Retiré',
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

        /* $operateurs = Operateur::where('statut_agrement', 'agréé')
            ->whereHas('operateurmodules', function ($query) use ($modulename) {
                $query->where('module', 'like', '%' . $modulename . '%')
                    ->where('statut', 'agréé');
            })->get();
 */
        // Convertir en minuscules
        $modulenameLower = strtolower($modulename);

// Supprimer uniquement les parenthèses, mais garder le contenu
        $modulenameClean = str_replace(['(', ')'], ' ', $modulenameLower);

        $articles = ['le', 'la', 'les', 'un', 'une', 'de', 'du', 'des', 'en', 'et', 'à', 'au', 'aux', 'pour', 'par', 'dans', 'sur', 'avec'];

        $keywords = array_filter(
            explode(' ', $modulenameClean),
            fn($word) => strlen($word) >= 3 && ! in_array($word, $articles)
        );

        $operateurs = Operateur::where('statut_agrement', 'agréé')
            ->whereHas('operateurmodules', function ($query) use ($keywords) {
                $query->where('statut', 'agréé');
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('module', 'like', '%' . $word . '%');
                    }
                });
            })
            ->get();
        /* dd($operateurs);

        $operateurs = Operateur::get();

        $keywords = explode(' ', $modulename);

        $query = Operateurmodule::where('statut', 'agréé');

        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->orWhere('module', 'like', '%' . $word . '%');
            }
        });

        $operateurmodules = $query->get(); */

        $operateurFormation = DB::table('formations')
            ->where('operateurs_id', $formation->operateurs_id)
            ->pluck('operateurs_id', 'operateurs_id')
            ->all();

        return view("formations.individuelles.add-operateurs", compact('formation', 'operateurs', 'module', 'localite', 'operateurFormation'));
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

        /* $operateurs = Operateur::get(); */

        /* $operateurmodules = Operateurmodule::where('module', $modulename)->where('statut', 'agréé')->get(); */
        /* $operateurmodules = Operateurmodule::where('module', 'like', '%' . $modulename . '%')
            ->where('statut', 'agréé')
            ->get(); */

        /*   $keywords = explode(' ', $modulename); // ['Teinture', 'Batik']

        $query = Operateurmodule::where('statut', 'agréé');

        foreach ($keywords as $word) {
            $query->where('module', 'like', '%' . $word . '%');
        }

        $operateurmodules = $query->get(); */

        /* $keywords = explode(' ', $modulename);

        $query = Operateurmodule::where('statut', 'agréé');

        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->orWhere('module', 'like', '%' . $word . '%');
            }
        });

        $operateurmodules = $query->get(); */

        /* $operateurs = Operateur::where('statut_agrement', 'agréé')
            ->whereHas('operateurmodules', function ($query) use ($modulename) {
                $query->where('module', 'like', '%' . $modulename . '%')
                    ->where('statut', 'agréé');
            })->get(); */

        // Convertir en minuscules
        $modulenameLower = strtolower($modulename);

// Supprimer uniquement les parenthèses, mais garder le contenu
        $modulenameClean = str_replace(['(', ')'], ' ', $modulenameLower);

        $articles = ['le', 'la', 'les', 'un', 'une', 'de', 'du', 'des', 'en', 'et', 'à', 'au', 'aux', 'pour', 'par', 'dans', 'sur', 'avec'];

        $keywords = array_filter(
            explode(' ', $modulenameClean),
            fn($word) => strlen($word) >= 3 && ! in_array($word, $articles)
        );

        $operateurs = Operateur::where('statut_agrement', 'agréé')
            ->whereHas('operateurmodules', function ($query) use ($keywords) {
                $query->where('statut', 'agréé');
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('module', 'like', '%' . $word . '%');
                    }
                });
            })
            ->get();

        $operateurFormation = DB::table('formations')
            ->where('operateurs_id', $formation->operateurs_id)
            ->pluck('operateurs_id', 'operateurs_id')
            ->all();

        return view("formations.collectives.add-operateur-collective", compact('formation', 'operateurs', 'collectivemodule', 'localite', 'operateurFormation'));
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

        Alert::success('Succès', 'Module ajouté avec succès');

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
            "statut" => 'Sélectionné',
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

        Alert::success('Succès', 'demande sélectionnée avec succès');

        return redirect()->back();

    }

    public function addmoduleformations($idformation, $idlocalite)
    {

        $formation = Formation::findOrFail($idformation);
        /* $module    = $formation?->module?->name; */
        $localite = Region::findOrFail($idlocalite);

        $modules = Module::select('id', 'uuid', 'domaines_id', 'name')
            ->whereNotNull('domaines_id')
            ->get();

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

        /* $collectivemodules = Collectivemodule::get(); */
        $collectivemodules = Collectivemodule::select('id', 'uuid', 'collectives_id', 'module', 'statut')->get();

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
            ->orwhere('collectivemodules.statut', 'Sélectionné')
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
                    "statut"        => 'Conforme',
                ]);

                $collectivemodule->save();

                $collectivemodule->update([
                    "formations_id" => $idformation,
                    "statut"        => 'Sélectionné',
                ]);

                $collectivemodule->save();

                Alert::success('Fait !', 'ajouté avec succès');

                return redirect()->back();
            } else {

                $collectivemodule->update([
                    "formations_id" => $idformation,
                    "statut"        => 'Sélectionné',
                ]);

                $collectivemodule->save();

                Alert::success('Fait !', 'ajouté avec succès');

                return redirect()->back();
            }

        } else {
            $collectivemodule->update([
                "formations_id" => $idformation,
                "statut"        => 'Sélectionné',
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
            Alert::warning('Désolé !', 'action non autorisée');
        } else {

            /* if ($formation->statut == "Terminée") {
            Alert::warning('Désolé !', 'Cette formation a déjà été exécutée.');
            } else */

            if ($formation->statut == 'Annulée') {
                Alert::warning('Désolé !', 'formation déjà annulée');
            } elseif ($formation->statut == 'Attente') {
                Alert::warning('Désolé !', 'la formation n\'a pas encore démarrée');
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
            Alert::warning('Désolé !', 'action non autorisée');
        } else {
            if ($formation->statut == "Terminée") {
                Alert::warning('Désolé !', 'Cette formation a déjà été exécutée.');
            } elseif ($formation->statut == "En cours") {
                Alert::warning('Désolé !', 'formation en cours...');
            } else {
                $formation->update([
                    'statut'       => "En cours",
                    'validated_by' => Auth::user()->firstname . ' ' . Auth::user()->name,
                ]);

                $formation->save();

                $validated_by = new Validationformation([
                    'validated_id'  => Auth::user()->id,
                    'action'        => "En cours",
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
            'notes'         => ['required', 'array'],
            'individuelles' => ['required', 'array'],
        ]);

        $individuelles = $request->input('individuelles');
        $notes         = $request->input('notes');

        if (count($individuelles) !== count($notes)) {
            return back()->withErrors('Le nombre de notes ne correspond pas au nombre de demandeurs.');
        }

        $individuelles_notes = array_combine($individuelles, $notes);

        foreach ($individuelles_notes as $key => $value) {
            $individuelle = Individuelle::find($key);
            if (! $individuelle) {
                continue;
            }

            // Calcul de l'appréciation
            if ($value <= 4) {
                $appreciation = "Médiocre";
            } elseif ($value <= 8) {
                $appreciation = "Insuffisant";
            } elseif ($value <= 11) {
                $appreciation = "Passable";
            } elseif ($value <= 13) {
                $appreciation = "Assez bien";
            } elseif ($value <= 16) {
                $appreciation = "Bien";
            } elseif ($value <= 19) {
                $appreciation = "Très bien";
            } elseif ($value == 20) {
                $appreciation = "Excellent";
            } else {
                $appreciation = "Non défini";
            }

            // Vérifie que la formation est terminée avant d'attribuer la note
            if ($individuelle->formation && $individuelle->formation->statut == "Terminée") {
                $individuelle->update([
                    "note_obtenue" => $value,
                    "appreciation" => $appreciation,
                    "statut"       => 'formé',
                ]);

                Validationindividuelle::create([
                    'validated_id'     => Auth::user()->id,
                    'action'           => 'formé',
                    'individuelles_id' => $individuelle->id,
                ]);
            } else {
                Alert::warning('Désolé !', 'La formation n\'est pas encore achevée pour l\'un des demandeurs.');
                return redirect()->back();
            }
        }

        Alert::success('Bravo !', 'L\'évaluation est terminée.');
        return redirect()->back();
    }

    public function givenotedemandeursCollective($idformation, Request $request)
    {
        $request->validate([
            'notes'            => ['required', 'array'],
            'listecollectives' => ['required', 'array'],
        ]);

        $listecollectives = $request->input('listecollectives');
        $notes            = $request->input('notes');

        if (count($listecollectives) !== count($notes)) {
            return back()->withErrors('Le nombre de notes ne correspond pas au nombre de demandeurs.');
        }

        $listecollectives_notes = array_combine($listecollectives, $notes);

        foreach ($listecollectives_notes as $key => $value) {
            $listecollective = Listecollective::find($key);
            if (! $listecollective) {
                continue;
            }

            if ($value <= 4) {
                $appreciation = "Médiocre";
            } elseif ($value <= 8) {
                $appreciation = "Insuffisant";
            } elseif ($value <= 11) {
                $appreciation = "Passable";
            } elseif ($value <= 13) {
                $appreciation = "Assez bien";
            } elseif ($value <= 16) {
                $appreciation = "Bien";
            } elseif ($value <= 19) {
                $appreciation = "Très bien";
            } elseif ($value == 20) {
                $appreciation = "Excellent";
            } else {
                $appreciation = "Non défini"; // cas limite
            }

            $listecollective->update([
                "note_obtenue" => $value,
                "appreciation" => $appreciation,
                "statut"       => 'formé',
            ]);

            $collectivemodule = $listecollective->collectivemodule;
            if ($collectivemodule) {
                $collectivemodule->update([
                    "statut" => 'formé',
                ]);

                $collective = $collectivemodule->collective;
                if ($collective) {
                    $collective->update([
                        "statut_demande" => 'formé',
                    ]);
                }
            }
        }

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
            /* 'evaluateur'        => ['required', 'string'], */
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

        /* if (! empty($request->input('onfpevaluateur')) && $request->input('onfpevaluateur') === "Aucun") {
            $onfpevaluateur = null;
        } else {
            $onfpevaluateur = $request->input('onfpevaluateur');
        } */

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
            /*  "evaluateurs_id"     => $request->input('evaluateur'),
            "onfpevaluateurs_id" => $onfpevaluateur, */
            "referentiels_id"    => $referentiel_id,
        ]);

        $formation->save();

        $formation->evaluateurs()->sync($request->evaluateur);
        $formation->onfpevaluateurs()->sync($request->onfpevaluateur);

        Alert::success('Succès !', 'Enregistrement effectué avec succès.');

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

        $formation = Formation::findOrFail($request->input('id'));

        $title = 'Fiche de suivi de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('id'));

        $title = 'Feuille de présence de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('id'));

        $title = 'Feuille de présence de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('idformation'));
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
        $options->setDefaultFont('DejaVu Sans');
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

        $formation            = Formation::findOrFail($request->input('idformation'));
        $emargementcollective = Emargementcollective::findOrFail($request->input('idemargement'));

        $feuillepresenceListecollective = DB::table('feuillepresencecollectives')
            ->where('emargementcollectives_id', $emargementcollective?->id)
            ->pluck('emargementcollectives_id', 'emargementcollectives_id')
            ->all();

        $feuillepresencecollectives = Feuillepresencecollective::where('emargementcollectives_id', $emargementcollective?->id)->get();

        $title = 'Feuille de présence de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.collectives.feuillepresencecoljour', compact(
            'formation',
            'emargementcollective',
            'feuillepresenceListecollective',
            'feuillepresencecollectives',
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

    public function fichePresenceJour(Request $request)
    {

        $formation = Formation::findOrFail($request->input('idformation'));
        /* $module     = Module::findOrFail($request->input('idmodule'));
        $region     = Region::findOrFail($request->input('idlocalite')); */
        $emargement = Emargement::findOrFail($request->input('idemargement'));

        $feuillepresenceIndividuelle = DB::table('feuillepresences')
            ->where('emargements_id', $emargement?->id)
            ->pluck('emargements_id', 'emargements_id')
            ->all();

        $title = 'Fiche de suivi de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.individuelles.fichepresencejour', compact(
            'formation',
            /* 'individuelles', */
            'emargement',
            'feuillepresenceIndividuelle',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'portrait');

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

    public function fichePresenceColJour(Request $request)
    {

        $formation            = Formation::findOrFail($request->input('idformation'));
        $emargementcollective = Emargementcollective::findOrFail($request->input('idemargement'));

        $feuillepresenceListecollective = DB::table('feuillepresencecollectives')
            ->where('emargementcollectives_id', $emargementcollective?->id)
            ->pluck('emargementcollectives_id', 'emargementcollectives_id')
            ->all();

        $feuillepresencecollectives = Feuillepresencecollective::where('emargementcollectives_id', $emargementcollective?->id)->get();

        $title = 'Fiche de suivi de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.collectives.fichepresencecoljour', compact(
            'formation',
            'emargementcollective',
            'feuillepresenceListecollective',
            'feuillepresencecollectives',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Fiche de suivi de la formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function feuillePresenceTous(Request $request)
    {

        $formation  = Formation::findOrFail($request->input('idformation'));
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

        $formation  = Formation::findOrFail($request->input('idformation'));
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

        $formation = Formation::findOrFail($request->input('idformation'));
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
        $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('idformation'));

        $title = 'Feuille de présence de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('idformation'));

        $title = 'Etat transport de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('idformation'));

        $title = 'Etat transport de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('id'));

        if ($formation->statut == "Terminée") {

            $title = 'PV Evaluation de la formation en  ' . $formation->name;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('id'));

        if ($formation->statut == "Terminée") {

            $title = 'PV Evaluation de la formation en  ' . $formation->name;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('id'));

        $title = 'Fiche de suivi de la formation en  ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('id'));

        if ($formation->statut == "Terminée") {

            $title = 'PV Evaluation de la formation en  ' . $formation->name;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('id'));

        $title = 'PV Evaluation de la formation en  ' . $formation->name;

        $membres_jury  = explode(";", $formation->membres_jury);
        $count_membres = count($membres_jury);

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
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

        $statutsVoulus = ['attente', 'conforme', 'nouvelle', 'validée'];

        $listecollectives = Listecollective::join('collectives', 'collectives.id', 'listecollectives.collectives_id')
            ->select('listecollectives.*')
            ->where('collectives.id', $collectivemodule->collective->id)
            ->where('collectivemodules_id', $idcollectivemodule)
            ->whereIn('collectives.statut_demande', $statutsVoulus)
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
                    "statut"        => 'Conforme',
                ]);
                $listecollectiveformation->save();
            }

            foreach ($request->listecollectives as $listecollective) {
                $listecollective = Listecollective::findOrFail($listecollective);

                $listecollective->update([
                    "formations_id" => $idformation,
                    "statut"        => 'Sélectionné',
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

    public function addajouterDemandeursPresenceJourCollectives(Request $request, $idformation, $idcollectivemodule, $idlocalite, $idemargementcollective)
    {
        $formation            = Formation::findOrFail($idformation);
        $collectivemodule     = Collectivemodule::findOrFail($idcollectivemodule);
        $localite             = Region::findOrFail($idlocalite);
        $emargementcollective = Emargementcollective::findOrFail($idemargementcollective);

        /* $ids      = json_decode($request->query('ids'), true);
        $feuilles = Feuillepresencecollective::whereIn('id', $ids)->get(); */

        $statutsVoulus = ['attente', 'conforme', 'nouvelle', 'validée', 'Sélectionné'];

        $listecollectives = Listecollective::join('collectives', 'collectives.id', 'listecollectives.collectives_id')
            ->select('listecollectives.*')
            ->where('listecollectives.collectivemodules_id', $idcollectivemodule)
            ->where('listecollectives.formations_id', $idformation)
            ->where('collectives.id', $collectivemodule->collective->id)
            ->whereIn('collectives.statut_demande', $statutsVoulus)
            ->get();

        /* $candidatsretenus = Listecollective::where('collectivemodules_id', $idcollectivemodule)
            ->where('formations_id', $idformation)
            ->get(); */

        $candidatsretenus = Feuillepresencecollective::where('emargementcollectives_id', $idemargementcollective)->get();

        /* $listecollectiveFormation = DB::table('listecollectives')
            ->where('formations_id', $idformation)
            ->pluck('formations_id', 'formations_id')
            ->all(); */

        /* $listecollectivesIdsDansEmargement = DB::table('feuillepresencecollectives')
            ->where('emargementcollectives_id', $idemargementcollective)
            ->pluck('listecollectives_id')
            ->toArray(); */

        /* $listecollectiveFormation = DB::table('listecollectives')
            ->where('formations_id', $idformation)
            ->whereIn('id', $listecollectivesIdsDansEmargement)
            ->get(); */

        /* $listecollectiveCochees = DB::table('listecollectives')
            ->whereIn('id', $listecollectivesIdsDansEmargement)
            ->pluck('id')
            ->toArray(); */

        $listecollectiveCochees = Feuillepresencecollective::where('emargementcollectives_id', $idemargementcollective)
            ->pluck('listecollectives_id')
            ->toArray();

        /* dd($listecollectivesIdsDansEmargement, $listecollectiveFormation, $listecollectiveCochees); */

        return view("formations.collectives.add-presencecollective-jour",
            compact('formation',
                'listecollectives',
                'collectivemodule',
                'emargementcollective',
                'listecollectiveCochees',
                'localite',
                'candidatsretenus'));
    }

    public function giveajouterDemandeursPresenceJourCollectives(Request $request, $idformation, $idcollectivemodule, $idlocalite, $idemargementcollective)
    {
        $request->validate([
            'listecollectives' => ['required'],
        ]);
        /*
        $formation            = Formation::findOrFail($idformation);
        $emargementcollective = Emargementcollective::findOrFail($idemargementcollective);

        foreach ($formation->listecollectives as $key => $listecollective) {
            $feuillepresence = Feuillepresencecollective::create([
                'emargementcollectives_id' => $emargement->id,
                'listecollectives_id'      => $listecollective->id,
            ]);
        } */

        $emargement = Emargementcollective::findOrFail($idemargementcollective);

        // IDs cochés dans le formulaire
        $idsCoches = $request->input('listecollectives', []);

        // IDs déjà enregistrés dans la feuille de présence
        $idsExistants = Feuillepresencecollective::where('emargementcollectives_id', $emargement->id)
            ->pluck('listecollectives_id')
            ->toArray();

        // Créer les absents (cochés mais pas encore enregistrés)
        $idsACreer = array_diff($idsCoches, $idsExistants);

        foreach ($idsACreer as $id) {
            Feuillepresencecollective::create([
                'emargementcollectives_id' => $emargement->id,
                'listecollectives_id'      => $id,
            ]);
        }

        // (Optionnel) Supprimer ceux qui ne sont plus cochés
        $idsASupprimer = array_diff($idsExistants, $idsCoches);

        Feuillepresencecollective::where('emargementcollectives_id', $emargement->id)
            ->whereIn('listecollectives_id', $idsASupprimer)
            ->delete();

        Alert::success('Succès', 'Feuille de présence mise à jour avec succès.');
        /* return redirect()->back(); */
        return redirect()->route('ajouter.presence.get', [
            'idformation'            => $idformation,
            'idcollectivemodule'     => $idcollectivemodule,
            'idlocalite'             => $idlocalite,
            'idemargementcollective' => $idemargementcollective,
        ]);
    }

    public function addajouterDemandeursPresenceJour(Request $request, $idformation, $idmodule, $idlocalite, $idemargement)
    {
        $formation  = Formation::findOrFail($idformation);
        $module     = Module::findOrFail($idmodule);
        $localite   = Region::findOrFail($idlocalite);
        $emargement = Emargement::findOrFail($idemargement);

        $statutsVoulus = ['attente', 'conforme', 'nouvelle', 'validée', 'Sélectionné'];

        /* $individuelles = Individuelle::where('modules_id', $idmodule)
            ->where('formations_id', $idformation)
            ->whereIn('statut', $statutsVoulus)
            ->get(); */

        $individuelles = Individuelle::where('formations_id', $idformation)
            ->whereIn('statut', $statutsVoulus)
            ->get();

        /*  $listecollectives = Listecollective::join('collectives', 'collectives.id', 'listecollectives.collectives_id')
            ->select('listecollectives.*')
            ->where('listecollectives.collectivemodules_id', $idcollectivemodule)
            ->where('listecollectives.formations_id', $idformation)
            ->where('collectives.id', $collectivemodule->collective->id)
            ->whereIn('collectives.statut_demande', $statutsVoulus)
            ->get(); */

        $candidatsretenus = Feuillepresence::where('emargements_id', $idemargement)->get();

        $individuelleCochees = Feuillepresence::where('emargements_id', $idemargement)
            ->pluck('individuelles_id')
            ->toArray();

        return view("formations.individuelles.add-presence-jour",
            compact('formation',
                'individuelles',
                'module',
                'emargement',
                'individuelleCochees',
                'localite',
                'candidatsretenus'));
    }

    public function giveajouterDemandeursPresenceJour(Request $request, $idformation, $idmodule, $idlocalite, $idemargement)
    {
        $request->validate([
            'individuelles' => ['required'],
        ]);

        $emargement = Emargement::findOrFail($idemargement);

        // IDs cochés dans le formulaire
        $idsCoches = $request->input('individuelles', []);

        // IDs déjà enregistrés dans la feuille de présence
        $idsExistants = Feuillepresence::where('emargements_id', $emargement->id)
            ->pluck('individuelles_id')
            ->toArray();

        // Créer les absents (cochés mais pas encore enregistrés)
        $idsACreer = array_diff($idsCoches, $idsExistants);

        foreach ($idsACreer as $id) {
            Feuillepresence::create([
                'emargements_id'   => $emargement->id,
                'individuelles_id' => $id,
            ]);
        }

        // (Optionnel) Supprimer ceux qui ne sont plus cochés
        $idsASupprimer = array_diff($idsExistants, $idsCoches);

        Feuillepresence::where('emargements_id', $emargement->id)
            ->whereIn('individuelles_id', $idsASupprimer)
            ->delete();

        Alert::success('Succès', 'Feuille de présence mise à jour avec succès.');
        /* return redirect()->back(); */
        return redirect()->route('ajout.presence.get', [
            'idformation'  => $idformation,
            'idmodule'     => $idmodule,
            'idlocalite'   => $idlocalite,
            'idemargement' => $idemargement,
        ]);
    }

    public function lettreEvaluation(Request $request)
    {

        $formation = Formation::findOrFail($request->input('id'));

        if ($formation->statut == "Terminée") {

            $title = 'Lettre de mission évaluation formation en  ' . $formation->name;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('DejaVu Sans');
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

        $formation = Formation::findOrFail($request->input('id'));

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

        $admis_count       = $admis_h_count + $admis_f_count;
        $pourcentage_admis = $formes_total > 0 ? round(($admis_count / $formes_total) * 100, 2) : 0;

        if ($formation->statut == "Terminée") {

            $title = 'Attestation de bonne execution ' . $formation?->operateur?->user?->username . ' - ' . $formation?->code;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('DejaVu Sans');
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view('formations.individuelles.abe', compact(
                'formation',
                'title',
                'membres_jury',
                'count_membres',
                'admis',
                'recales',
                'admis_h_count',
                'pourcentage_admis',
                'admis_count',
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

            $name = 'Attestation de bonne execution ' . $formation?->operateur?->user?->username . ' - ' . $formation?->code . '.pdf';

            // Output the generated PDF to Browser
            $dompdf->stream($name, ['Attachment' => false]);
        } else {
            Alert::warning('Désolé !', "La formation n'est pas encore terminée.");
            return redirect()->back();
        }
    }

    public function abeEvaluationlettre(Request $request, $idformation)
    {

        $formation = Formation::findOrFail($idformation);

// Toutes les individuelles liées à la formation, avec jointure utilisateur
        $individuelles = Individuelle::with('user')
            ->where('formations_id', $idformation)
            ->get();

// Admis et recalés
        $admis   = $individuelles->where('note_obtenue', '>=', 12);
        $recales = $individuelles->where('note_obtenue', '<', 12);

// Comptage par sexe parmi les admis
        $admis_h_count = $admis->filter(fn($i) => $i->user?->civilite === 'M.')->count();
        $admis_f_count = $admis->filter(fn($i) => $i->user?->civilite === 'Mme')->count();

// Comptage total formés par sexe
        $formes_h_count = $individuelles->filter(fn($i) => $i->user?->civilite === 'M.')->count();
        $formes_f_count = $individuelles->filter(fn($i) => $i->user?->civilite === 'Mme')->count();
        $formes_total   = $formes_h_count + $formes_f_count;

// Comptage des retenus (identique à formés dans ton code, à confirmer)
        $retenus_h_count = $formes_h_count;
        $retenus_f_count = $formes_f_count;
        $retenus_total   = $retenus_h_count + $retenus_f_count;

        $admis_count       = $admis_h_count + $admis_f_count;
        $pourcentage_admis = $formes_total > 0 ? round(($admis_count / $formes_total) * 100, 2) : 0;

        if ($formation->statut == "Terminée") {

            $title = 'Attestation de bonne execution ' . $formation?->operateur?->user?->username . ' - ' . $formation?->code;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('DejaVu Sans');
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view('formations.individuelles.abe', compact(
                'formation',
                'title',
                'membres_jury',
                'count_membres',
                'admis',
                'recales',
                'admis_count',
                'pourcentage_admis',
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

            $name = 'Attestation de bonne execution ' . $formation?->operateur?->user?->username . ' - ' . $formation?->code . '.pdf';

            // Output the generated PDF to Browser
            return $dompdf->stream($name, ['Attachment' => false]);

        } else {
            Alert::warning('Désolé !', "La formation n'est pas encore terminée.");
            return redirect()->back();
        }
    }

    public function abeEvaluationCol(Request $request)
    {

        $formation = Formation::findOrFail($request->input('id'));

        $admis = Listecollective::where('formations_id', $formation->id)
            ->where('note_obtenue', '>=', '12')
            ->get();

        $recales = Listecollective::where('formations_id', $formation->id)
            ->where('note_obtenue', '<', '12')
            ->get();

        $admis_h_count = Listecollective::where('formations_id', $formation->id)
            ->where('.civilite', "M.")
            ->where('note_obtenue', '>=', '12')
            ->count();

        $admis_f_count = Listecollective::where('formations_id', $formation->id)
            ->where('civilite', "Mme")
            ->where('note_obtenue', '>=', '12')
            ->count();

        $formes_h_count = Listecollective::where('formations_id', $formation->id)
            ->where('civilite', "M.")
            ->count();

        $formes_f_count = Listecollective::where('formations_id', $formation->id)
            ->where('civilite', "Mme")
            ->count();

        $formes_total = $formes_h_count + $formes_f_count;

        $retenus_h_count = Listecollective::where('formations_id', $formation->id)
            ->where('civilite', "M.")
            ->count();

        $retenus_f_count = Listecollective::where('formations_id', $formation->id)
            ->where('civilite', "Mme")
            ->count();

        $retenus_total = $retenus_h_count + $retenus_f_count;

        $admis_count       = $admis_h_count + $admis_f_count;
        $pourcentage_admis = $formes_total > 0 ? round(($admis_count / $formes_total) * 100, 2) : 0;

        if ($formation->statut == "Terminée") {

            $title = 'Attestation de bonne execution ' . $formation?->operateur?->user?->username . ' - ' . $formation?->code;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('DejaVu Sans');
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view('formations.collectives.abecollective', compact(
                'formation',
                'title',
                'membres_jury',
                'count_membres',
                'admis',
                'recales',
                'pourcentage_admis',
                'admis_count',
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

            $name = 'Attestation de bonne execution ' . $formation?->operateur?->user?->username . ' - ' . $formation?->code . '.pdf';

            // Output the generated PDF to Browser
            $dompdf->stream($name, ['Attachment' => false]);
        } else {
            Alert::warning('Désolé !', "La formation n'est pas encore terminée.");
            return redirect()->back();
        }
    }

    public function abeEvaluationCollettre(Request $request, $idformation)
    {

        $formation = Formation::findOrFail($idformation);

        $admis = Listecollective::where('formations_id', $formation->id)
            ->where('note_obtenue', '>=', '12')
            ->get();

        $recales = Listecollective::where('formations_id', $formation->id)
            ->where('note_obtenue', '<', '12')
            ->get();

        $admis_h_count = Listecollective::where('formations_id', $formation->id)
            ->where('.civilite', "M.")
            ->where('note_obtenue', '>=', '12')
            ->count();

        $admis_f_count = Listecollective::where('formations_id', $formation->id)
            ->where('civilite', "Mme")
            ->where('note_obtenue', '>=', '12')
            ->count();

        $formes_h_count = Listecollective::where('formations_id', $formation->id)
            ->where('civilite', "M.")
            ->count();

        $formes_f_count = Listecollective::where('formations_id', $formation->id)
            ->where('civilite', "Mme")
            ->count();

        $formes_total = $formes_h_count + $formes_f_count;

        $retenus_h_count = Listecollective::where('formations_id', $formation->id)
            ->where('civilite', "M.")
            ->count();

        $retenus_f_count = Listecollective::where('formations_id', $formation->id)
            ->where('civilite', "Mme")
            ->count();

        $retenus_total = $retenus_h_count + $retenus_f_count;

        $admis_count       = $admis_h_count + $admis_f_count;
        $pourcentage_admis = $formes_total > 0 ? round(($admis_count / $formes_total) * 100, 2) : 0;

        if ($formation->statut == "Terminée") {

            $title = 'Attestation de bonne execution ' . $formation?->operateur?->user?->username . ' - ' . $formation?->code;

            $membres_jury  = explode(";", $formation->membres_jury);
            $count_membres = count($membres_jury);

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $options->setDefaultFont('DejaVu Sans');
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view('formations.collectives.abecollective', compact(
                'formation',
                'title',
                'membres_jury',
                'count_membres',
                'admis',
                'recales',
                'admis_count',
                'pourcentage_admis',
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

            $name = 'Attestation de bonne execution ' . $formation?->operateur?->user?->username . ' - ' . $formation?->code . '.pdf';

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
                ->where('individuelles.statut', 'formé')
                ->where('individuelles.modules_id', 'LIKE', "%{$module?->id}%")
                ->where('individuelles.regions_id', $region?->id)
                ->where('individuelles.projets_id', $projet?->id)
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();

        } elseif (! empty($request->region)) {
            $region = Region::where('nom', $request->region)->first();

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formé')
                ->where('individuelles.regions_id', $region?->id)
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } elseif (! empty($request->projet)) {
            $projet = Projet::where('sigle', $request->projet)->first();

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formé')
                ->where('individuelles.projets_id', $projet?->id)
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } elseif (! empty($request->module)) {
            $module = Module::where('name', $request->module)->first();

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formé')
                ->where('individuelles.modules_id', 'LIKE', "%{$module?->id}%")
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } else {
            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formé')
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
                ->where('listecollectives.statut', 'formé')
                ->where('listecollectives.collectivemodules_id', 'LIKE', "%{$module?->id}%")
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();

        } else {
            $formes = Listecollective::join('formations', 'formations.id', 'listecollectives.formations_id')
                ->select('listecollectives.*')
                ->where('listecollectives.statut', 'formé')
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
                ->where('individuelles.statut', 'formé')
                ->where('individuelles.modules_id', 'LIKE', "%{$module?->id}%")
                ->where('individuelles.regions_id', $region?->id)
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } elseif (isset($request->region)) {
            $region              = Region::where('nom', $request->region)->first();
            $title_region_module = ' dans la région de ' . $request->region;

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formé')
                ->where('individuelles.regions_id', $region?->id)
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } elseif (isset($request->module)) {
            $module              = Module::where('name', $request->module)->first();
            $title_region_module = ' en ' . $request->module;

            $formes = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formé')
                ->where('individuelles.modules_id', 'LIKE', "%{$module?->id}%")
                ->whereBetween(DB::raw('DATE(formations.date_debut)'), [$request->from_date, $request->to_date])
                ->get();
        } else {
            $title_region_module = '';
            $formes              = Individuelle::join('formations', 'formations.id', 'individuelles.formations_id')
                ->select('individuelles.*')
                ->where('individuelles.statut', 'formé')
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
            /* 'date' => 'nullable|date|size:10|date_format:Y-m-d', */
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
            /* 'date' => 'nullable|date|size:10|date_format:Y-m-d', */
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

    public function parType($libelle)
    {
        // Récupère l'objet TypesFormation correspondant (ex: 'individuelle' ou 'collective')
        $type = TypesFormation::where('name', $libelle)->firstOrFail();

        // Récupère les formations associées à ce type
        $formations = Formation::where('types_formations_id', $type->id)->get();

        return view('formations.liste', compact('formations', 'libelle'));
    }

    public function listePresence(Request $request)
    {

        $formation = Formation::findOrFail($request->input('id'));

        $title = $formation?->module?->name . ', liste des candidats sélectionnés en ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.individuelles.liste-selectionne', compact(
            'formation',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = $formation?->module?->name . ', liste des candidats sélectionnés, ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function listePresenceCol(Request $request)
    {

        $formation = Formation::findOrFail($request->input('id'));

        $title = $formation?->operateurmodule?->module?->name . ', liste des candidats sélectionnés en ' . $formation->name;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.individuelles.liste-selectionne', compact(
            'formation',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = $formation?->operateurmodule?->module?->name . ', liste des candidats sélectionnés, ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function editEvaluationForm($formationId)
    {

        $formation = Formation::with('evaluateurs')->findOrFail($formationId);

        return view('formations.lettrevaluations.edit_evaluation', compact('formation'));
    }

    public function updateEvaluationForm(Request $request, $formationId)
    {
        $request->validate([
            'evaluations'                 => 'required|array',
            'evaluations.*.evaluateur_id' => 'required|exists:evaluateurs,id',
            'evaluations.*.numero_lettre' => 'required|string|max:255',
            'evaluations.*.date_lettre'   => 'required|date',
        ]);

        $formation = Formation::findOrFail($formationId);

        $pivotData = [];

        foreach ($request->evaluations as $evaluateurId => $data) {
            $formation->evaluateurs()->updateExistingPivot($evaluateurId, [
                'numero_lettre' => $data['numero_lettre'],
                'date_lettre'   => $data['date_lettre'],
            ]);
        }

        $formation->evaluateurs()->syncWithoutDetaching($pivotData); // Met à jour sans supprimer

        /* return redirect()->route('formations.evaluations.edit', $formationId)
            ->with('success', 'Lettres de mission mises à jour.'); */
        Alert::success('Mise à jour réussie !', 'Les lettres de mission ont été mises à jour avec succès.');
        return redirect()->back();
    }

    public function downloadLettre($formationId)
    {
        $formation = Formation::with('evaluateurs')->findOrFail($formationId);

        /* $pdf = Pdf::loadView('pdf.lettres_mission', compact('formation'));

        return $pdf->download('lettres_mission_formation_' . $formation->id . '.pdf'); */

        $title = 'Lettre de mission évaluation formation en ' . $formation->name;

        $membres_jury  = explode(";", $formation->membres_jury);
        $count_membres = count($membres_jury);

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.lettrevaluations.lettremission', compact(
            'formation',
            'title',
            'membres_jury',
            'count_membres',
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Lettre_mission_formation_code_' . $formation->code . '.pdf';
        // Output the generated PDF to Browser
        return $dompdf->stream($name, ['Attachment' => false]);

    }

    public function downloadDemandePaiement($formationId)
    {
        $formation = Formation::with('evaluateurs')->findOrFail($formationId);

        $title         = 'Demande de paiement évaluation formation en ' . $formation->name;
        $membres_jury  = explode(";", $formation->membres_jury);
        $count_membres = count($membres_jury);
// ✅ Génération QR PNG sans imagick avec endroid/qr-code
        if ($formation?->module && $formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule && $formation?->collectivemodule?->module) {
            $moduleName = $formation?->collectivemodule?->module;
        }

        $qrContent = "Formation : {$formation?->name}\n" .
        "Code : {$formation?->code}\n" .
        "Module : {$moduleName}\n" .
        "Date : " . $formation?->date_debut?->format('d/m/Y') . " au " . $formation?->date_fin?->format('d/m/Y');

        $qrCode       = QrCode::create($qrContent)->setSize(150);
        $writer       = new PngWriter();
        $result       = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        // 🔢 Calculs
        $brut        = $formation?->frais_evaluateur ?? 0;
        $montant_ir  = round($brut * 0.05);
        $montant_net = $brut - $montant_ir;

        // 🔤 Conversion en lettres (via number-to-words)
        $numberToWords     = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('fr');
        $montant_lettres   = ucfirst($numberTransformer->toWords($brut)) . ' francs CFA';

        $html = View::make('formations.lettrevaluations.demandepaiement', compact(
            'formation',
            'title',
            'membres_jury',
            'count_membres',
            'brut',
            'montant_ir',
            'montant_net',
            'montant_lettres',
            'qrCodeBase64'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $name = 'Demande_paiement_' . $formation->code . '.pdf';
        return $dompdf->stream($name, ['Attachment' => false]);

    }

}
