<?php
namespace App\Http\Controllers;

use App\Http\Requests\ArriveOperateurStoreRequest;
use App\Http\Requests\StoreArriveRequest;
use App\Mail\ImputationcourrierMail;
use App\Models\Arrive;
use App\Models\Courrier;
use App\Models\Direction;
use App\Models\Employee;
use App\Models\Operateur;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ArriveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|courrier|a-courrier|Employe']);
    }

    public function index()
    {
        $anneeEnCours = date('Y');
        $an           = date('y');

// Récupération du dernier numéro de courrier pour l'année en cours
        $numCourrier = Arrive::join('courriers', 'courriers.id', '=', 'arrives.courriers_id')
            ->select('arrives.numero_arrive')
            ->where('courriers.annee', $anneeEnCours)
            ->orderByDesc('arrives.numero_arrive') // Tri décroissant pour récupérer le dernier
            ->first();                             // Récupérer le premier (dernier selon l'ordre)

        if ($numCourrier) {
            // Si un courrier existe, incrémenter son numéro
            $numCourrier = ++$numCourrier->numero_arrive;
        } else {
            // Si aucun courrier n'existe, initialiser avec l'année et le numéro 0001
            $numCourrier = $an . "0001";
        }

// Mise en forme du numéro de courrier en ajoutant des zéros au début
        $numCourrier = str_pad($numCourrier, 6, '0', STR_PAD_LEFT);

        // Récupérer le total des arrivées sans type et les derniers 100 courriers en une seule requête
        $totalCount  = Arrive::where('type', null);
        $total_count = number_format($totalCount->count(), 0, ',', ' ');

        $arrives        = $totalCount->latest()->take(100)->get();
        $count_courrier = number_format($arrives->count(), 0, ',', ' ');

// Compter les arrivées de type 'operateur'
        $count_arrives = Arrive::where('type', 'operateur')->count();

// Logique de titre
        if ($count_courrier < 1) {
            $title = 'Aucun courrier';
        } elseif ($count_courrier == 1) {
            $title = "{$count_courrier} courrier sur un total de {$total_count}";
        } else {
            $title = "{$count_courrier} derniers courriers sur un total de {$total_count}";
        }

        $today = date('Y-m-d');

// Compter les arrivées du jour
        $count_today = Arrive::where('type', null)
            ->where('created_at', 'LIKE', "{$today}%")
            ->count();

        return view(
            "courriers.arrives.index",
            compact("arrives", "count_today", "anneeEnCours", "numCourrier", "count_arrives", "title", "total_count")
        );

    }

    public function create()
    {
        $anneeEnCours = date('Y');
        $an           = date('y');

// Récupérer le dernier numéro de courrier pour l'année en cours
        $numCourrier = Arrive::join('courriers', 'courriers.id', '=', 'arrives.courriers_id')
            ->select('arrives.numero_arrive')
            ->where('courriers.annee', $anneeEnCours)
            ->latest('arrives.numero_arrive') // Tri par le dernier numéro
            ->first();                        // Récupérer le premier (dernier courrier)

        if ($numCourrier) {
            // Si un courrier existe, incrémenter son numéro
            $numCourrier = ++$numCourrier->numero_arrive;
        } else {
            // Si aucun courrier n'existe, initialiser avec l'année et le numéro 0001
            $numCourrier = $an . "0001";
        }

// Mise en forme du numéro de courrier en ajoutant des zéros au début
        $numCourrier = str_pad($numCourrier, 6, '0', STR_PAD_LEFT);

        return view("courriers.arrives.create", compact('anneeEnCours', 'numCourrier'));
    }

    public function store(StoreArriveRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /*  if (! empty($request->input('date_reponse'))) {
            $date_reponse = $request->input('date_reponse');
        } else {
            $date_reponse = null;
        } */

        $date_reponse = $request->input('date_reponse') ?: null;

        $courrier = Courrier::create([
            'numero_courrier' => $request->input('numero_courrier'),
            'date_recep'      => $request->input('date_arrivee'),
            'date_cores'      => $request->input('date_correspondance'),
            'annee'           => $request->input('annee'),
            'objet'           => strtoupper($request->input('objet')),
            'expediteur'      => strtoupper($request->input('expediteur')),
            'reference'       => strtoupper($request->input('reference')),
            'numero_reponse'  => $request->input('numero_reponse'),
            'date_reponse'    => $date_reponse,
            'observation'     => strtoupper($request->input('observation')),
            'type'            => 'arrive',
            "user_create_id"  => Auth::user()->id,
            "user_update_id"  => Auth::user()->id,
            'users_id'        => Auth::user()->id,
        ]);

        $arrive = Arrive::create([
            'numero_arrive' => $request->input('numero_arrive'),
            'courriers_id'  => $courrier->id,
        ]);

        Alert::success("Succès !", "Courrier ajouté avec succès.");

        return redirect()->back();
    }

    public function addCourrierOperateur(ArriveOperateurStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /* if (! empty($request->input('date_reponse'))) {
            $date_reponse = $request->input('date_reponse');
        } else {
            $date_reponse = null;
        } */

        $date_reponse = $request->input('date_reponse') ?: null;

        $user = User::create([
            'username'   => strtoupper($request->input("sigle")),
            'email'      => $request->input('email'),
            "operateur"  => $request->input("expediteur"),
            "fixe"       => $request->input("fixe"),
            'password'   => Hash::make($request->input('email')),
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ]);

        $anneeEnCours          = date('y');
        $annee                 = date('Y');
        $numero_agrement       = $request->input("numero_arrive") . '.' . $anneeEnCours . '/ONFP/DG/DEC/' . $annee;
        $numero_coreespondance = $request->input("numero_arrive");

        $courrier = Courrier::create([
            'date_recep'      => $request->input('date_arrivee'),
            'date_cores'      => $request->input('date_correspondance'),
            'numero_courrier' => $numero_coreespondance,
            'annee'           => $request->input('annee'),
            'objet'           => strtoupper($request->input('objet')),
            'expediteur'      => strtoupper($request->input('expediteur')),
            'numero_reponse'  => $request->input('numero_reponse'),
            'date_reponse'    => $date_reponse,
            'observation'     => strtoupper($request->input('observation')),
            'type'            => 'arrive',
            "user_create_id"  => Auth::user()->id,
            "user_update_id"  => Auth::user()->id,
            'users_id'        => Auth::user()->id,
        ]);

        $arrive = Arrive::create([
            'numero_arrive' => $request->input('numero_arrive'),
            'type'          => 'operateur',
            'courriers_id'  => $courrier->id,
        ]);

        $operateur = Operateur::create([
            "numero_agrement" => $numero_agrement,
            "type_demande"    => $request->input("type_demande"),
            "numero_dossier"  => $request->input("numero_dossier"),
            "annee_agrement"  => date('Y-m-d'),
            "statut_agrement" => 'Nouveau',
            "users_id"        => $user->id,
            'courriers_id'    => $courrier->id,
        ]);

        $user->assignRole('Operateur');

        Alert::success("Bravo !", "Le courrier a été ajouté avec succès.");

        return redirect()->back();
    }

    public function edit($id)
    {
        $arrive = Arrive::findOrFail($id);

        return view("courriers.arrives.update", compact("arrive"));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        /* $arrive = Arrive::findOrFail($id);

        foreach (Auth::user()->roles as $role) {
            if (! empty($role?->name) && ($role?->name != 'super-admin') && ($role?->name != 'Employe')
                && ($role?->name != 'admin') && ($role?->name != 'DIOF') && ($role?->name != 'DEC')) {
                $this->authorize('update', $arrive);
            }
        }

        $courrier = Courrier::findOrFail($arrive->courriers_id);

        $imp = $request->input('imp'); */

        $arrive = Arrive::findOrFail($id);
        $arrive->update([
            'jour_imputation' => Carbon::now(),
        ]);

        $arrive->save();

// Vérification des rôles autorisés
        $unauthorizedRoles = ['super-admin', 'Employe', 'admin', 'DIOF', 'DEC'];
        $roles             = Auth::user()->roles->pluck('name')->toArray();

        if (empty(array_intersect($roles, $unauthorizedRoles))) {
            $this->authorize('update', $arrive);
        }

        $courrier = Courrier::findOrFail($arrive->courriers_id);
        $imp      = $request->input('imp');

        if (isset($imp) && $imp == "1") {

            $this->validate($request, [
                "date_imp"    => ["required", "date", "size:10", "date_format:Y-m-d"],
                "description" => ["required", "string"],
                "id_emp"      => ["required"],
                "observation" => ["nullable", "string"],
            ]);

            $courrier = $arrive->courrier;
            $arrive->employees()->sync($request->id_emp);
            $arrive->users()->sync($request->id_emp);
            $courrier->directions()->sync($request->id_direction);
            $courrier->description = strtoupper($request->input('description'));
            $courrier->date_imp    = $request->input('date_imp');
            $courrier->observation = strtoupper($request->input('observation'));
            $courrier->save();

            $objetCourrier = $arrive->courrier->objet ?? 'objet';
            $lienApp       = url('https://sigof.onfp.sn/');                                // Remplace ceci par l'URL de ton application
            $lienCourrier  = url("https://sigof.onfp.sn/arrives/{$arrive->courrier->id}"); // Assure-toi que l'ID est bien accessible

            /*  collect($arrive->employees)
                ->filter(fn($user) => isset($user->email) && ! empty($user->email)) // Filtrer les utilisateurs sans email
                ->each(function ($user) use ($objetCourrier, $lienApp, $lienCourrier) {
                    $toEmail = $user->email;
                    $toUserName = "Bonjour ! " . ($user->civilite ?? '') . " " . ($user->firstname ?? '') . " " . ($user->name ?? '');

                    $safeMessage = "Le <b>Directeur Général</b> de l'ONFP vous a imputé un nouveau courrier. <br>
                    Merci de vous connecter à votre compte (<a href='{$lienApp}'>ici</a>) pour voir les détails
                    ou accéder directement au courrier via <a href='{$lienCourrier}'>ce lien</a>.";

                    $subject = "COURRIER ONFP | $objetCourrier";
                    $message = strip_tags($safeMessage, '<b><i><p><a><br>'); // Autorise <b>, <i>, <p>, et <a>

                    Mail::to($toEmail)->send(new ImputationcourrierMail($message, $subject, $toEmail, $toUserName));
                }); */

            foreach ($arrive->employees as $employe) {
                $toEmail    = $employe?->user->email;
                $toUserName = "Bonjour ! " . ($employe?->user->civilite ?? '') . " " . ($employe?->user->firstname ?? '') . " " . ($employe?->user->name ?? '');

                $safeMessage = "Le <b>Directeur Général</b> de l'ONFP vous a imputé un nouveau courrier. <br>
                    Merci de vous connecter à votre compte (<a href='{$lienApp}'>ici</a>) pour voir les détails
                    ou accéder directement au courrier via <a href='{$lienCourrier}'>ce lien</a>.";

                $subject = "COURRIER ONFP | $objetCourrier";
                $message = strip_tags($safeMessage, '<b><i><p><a><br>'); // Autorise <b>, <i>, <p>, et <a>

                Mail::to($toEmail)->send(new ImputationcourrierMail($message, $subject, $toEmail, $toUserName));
            }

            Alert::success('Bravo !', 'Le courrier a été imputé avec succès.');

            return redirect()->back();

        }

        /*  $this->validate($request, [
            "date_arrivee"        => ["required", "date", "size:10", "date_format:Y-m-d"],
            "date_correspondance" => ["required", "date", "size:10", "date_format:Y-m-d"],
            "numero_courrier"     => ["nullable", "string", "min:4", "max:25", "unique:courriers,numero_courrier,{$arrive->courrier->id}"],
            "numero_arrive"       => ["required", "string", "min:4", "max:25", "unique:arrives,numero_arrive,{$arrive->id}"],
            "numero_reponse"      => ["string", "min:6", "max:9", "nullable", "unique:courriers,numero_reponse,{$courrier->id}"],
            "file"                => ['sometimes', 'file', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:2048'],
            "annee"               => ["required", "string"],
            "expediteur"          => ["required", "string"],
            "objet"               => ["required", "string"],
            "date_reponse"        => ["nullable", "date"],
            "observation"         => ["nullable", "string"],
        ]);

        if (! empty($request->input('date_reponse'))) {
            $date_reponse = $request->input('date_reponse');
        } else {
            $date_reponse = null;
        }

        if (isset($courrier->file)) {
            $this->validate($request, [
                "legende" => ["required", "string"],
            ]);
        }

        if (request('file') && ! empty($courrier->file)) {
            $this->validate($request, [
                "legende" => ["required", "string"],
            ]);
            Storage::disk('public')->delete($courrier->file);
            $filePath = request('file')->store('courriers', 'public');

            $file            = $request->file('file');
            $filenameWithExt = $file->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Remove unwanted characters
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            // Get the original image extension
            $extension = $file->getClientOriginalExtension();

            // Create unique file name
            $fileNameToStore = 'courriers/' . $filename . '' . time() . '.' . $extension;

            $courrier->update(
                [
                    'date_recep'      => $request->input('date_arrivee'),
                    'date_cores'      => $request->input('date_correspondance'),
                    'numero_courrier' => $request->input('numero_courrier'),
                    'annee'           => $request->input('annee'),
                    'objet'           => strtoupper($request->input('objet')),
                    'expediteur'      => strtoupper($request->input('expediteur')),
                    'reference'       => strtoupper($request->input('reference')),
                    'numero_reponse'  => $request->input('numero_reponse'),
                    'date_reponse'    => $date_reponse,
                    'observation'     => strtoupper($request->input('observation')),
                    'file'            => $filePath,
                    'legende'         => $request->input('legende'),
                    'type'            => 'arrive',
                    "user_create_id"  => Auth::user()->id,
                    "user_update_id"  => Auth::user()->id,
                    'users_id'        => Auth::user()->id,
                ]
            );
        } elseif (request('file') && empty($courrier->file)) {
            $this->validate($request, [
                "legende" => ["required", "string"],
            ]);
            $filePath = request('file')->store('courriers', 'public');

            $file            = $request->file('file');
            $filenameWithExt = $file->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Remove unwanted characters
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            // Get the original image extension
            $extension = $file->getClientOriginalExtension();

            // Create unique file name
            $fileNameToStore = 'courriers/' . $filename . '' . time() . '.' . $extension;

            $courrier->update(
                [
                    'date_recep'      => $request->input('date_arrivee'),
                    'date_cores'      => $request->input('date_correspondance'),
                    'numero_courrier' => $request->input('numero_courrier'),
                    'annee'           => $request->input('annee'),
                    'objet'           => strtoupper($request->input('objet')),
                    'expediteur'      => strtoupper($request->input('expediteur')),
                    'reference'       => strtoupper($request->input('reference')),
                    'numero_reponse'  => $request->input('numero_reponse'),
                    'date_reponse'    => $date_reponse,
                    'observation'     => strtoupper($request->input('observation')),
                    'file'            => $filePath,
                    'legende'         => $request->input('legende'),
                    'type'            => 'arrive',
                    "user_create_id"  => Auth::user()->id,
                    "user_update_id"  => Auth::user()->id,
                    'users_id'        => Auth::user()->id,
                ]
            );
        } else {
            $courrier->update(
                [
                    'date_recep'      => $request->input('date_arrivee'),
                    'date_cores'      => $request->input('date_correspondance'),
                    'numero_courrier' => $request->input('numero_courrier'),
                    'annee'           => $request->input('annee'),
                    'objet'           => strtoupper($request->input('objet')),
                    'expediteur'      => strtoupper($request->input('expediteur')),
                    'reference'       => strtoupper($request->input('reference')),
                    'numero_reponse'  => $request->input('numero_reponse'),
                    'date_reponse'    => $date_reponse,
                    'observation'     => strtoupper($request->input('observation')),
                    'legende'         => $request->input('legende'),
                    'type'            => 'arrive',
                    "user_create_id"  => Auth::user()->id,
                    "user_update_id"  => Auth::user()->id,
                    'users_id'        => Auth::user()->id,
                ]
            );
        }

        $arrive->update(
            [
                'numero_arrive' => $request->input('numero_arrive'),
                'courriers_id'  => $courrier->id,
            ]
        );

        Alert::success('Bravo !', 'La mise à jour a été effectuée avec succès.');

        if ($arrive->type == 'operateur') {
            return Redirect::route('arrivesop');
        } else {
            return Redirect::back();
        } */
        $this->validate($request, [
            "date_arrivee"        => ["required", "date", "size:10", "date_format:Y-m-d"],
            "date_correspondance" => ["required", "date", "size:10", "date_format:Y-m-d"],
            "numero_courrier"     => ["nullable", "string", "min:4", "max:25", "unique:courriers,numero_courrier,{$arrive->courrier->id}"],
            "numero_arrive"       => ["required", "string", "min:4", "max:25", "unique:arrives,numero_arrive,{$arrive->id}"],
            "numero_reponse"      => ["string", "min:6", "max:9", "nullable", "unique:courriers,numero_reponse,{$courrier->id}"],
            "file"                => ['sometimes', 'file', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:4096'],
            "annee"               => ["required", "string"],
            "expediteur"          => ["required", "string"],
            "objet"               => ["required", "string"],
            "date_reponse"        => ["nullable", "date"],
            "observation"         => ["nullable", "string"],
        ]);

        $date_reponse = $request->input('date_reponse') ?: null;

        if ($request->hasFile('file')) {
            $this->validate($request, [
                "legende" => ["required", "string"],
            ]);

            // Si un fichier existe déjà, le supprimer
            if (! is_null($courrier->file)) {
                Storage::disk('public')->delete($courrier->file);
            }

            // Traitement du fichier
            $file            = $request->file('file');
            $filenameWithExt = $file->getClientOriginalName();
            $filename        = preg_replace("/[^A-Za-z0-9 ]/", '', pathinfo($filenameWithExt, PATHINFO_FILENAME));
            $filename        = preg_replace("/\s+/", '-', $filename);
            /* $extension       = $file->getClientOriginalExtension();   */
            $filename = time() . '_' . $file->getClientOriginalName();
            /* $filePath        = $file->storeAs('courriers', $filename . time() . '.' . $extension, 'public'); */
            $filePath = $file->storeAs('courriers', $filename, 'public');
        }

        $data = [
            'date_recep'      => $request->input('date_arrivee'),
            'date_cores'      => $request->input('date_correspondance'),
            'numero_courrier' => $request->input('numero_courrier'),
            'annee'           => $request->input('annee'),
            'objet'           => strtoupper($request->input('objet')),
            'expediteur'      => strtoupper($request->input('expediteur')),
            'reference'       => strtoupper($request->input('reference')),
            'numero_reponse'  => $request->input('numero_reponse'),
            'date_reponse'    => $date_reponse,
            'observation'     => strtoupper($request->input('observation')),
            'file'            => $filePath ?? $courrier->file,
            'legende'         => $request->input('legende'),
            'type'            => 'arrive',
            "user_create_id"  => Auth::user()->id,
            "user_update_id"  => Auth::user()->id,
            'users_id'        => Auth::user()->id,
        ];

        $courrier->update($data);

        $arrive->update([
            'numero_arrive' => $request->input('numero_arrive'),
            'courriers_id'  => $courrier->id,
        ]);

        Alert::success('Bravo !', 'La mise à jour a été effectuée avec succès.');

        if ($arrive->type == 'operateur') {
            return Redirect::route('arrivesop');
        } else {
            return Redirect::back();
        }

    }
    public function show($id)
    {
        $arrive = Arrive::findOrFail($id);

        $courrier = Courrier::findOrFail($arrive->courriers_id);

        $user_create = User::find($courrier->user_create_id);
        $user_update = User::find($courrier->user_update_id);

        $user_create_name = $user_create->firstname . ' ' . $user_create->name;
        $user_update_name = $user_update->firstname . ' ' . $user_update->name;

        return view("courriers.arrives.show", compact("arrive", "courrier", "user_create_name", "user_update_name"));
    }

    public function destroy($arriveId)
    {
        $arrive   = Arrive::findOrFail($arriveId);
        $courrier = $arrive->courrier;

        if (! empty($courrier->file)) {
            Storage::disk('public')->delete($courrier->file);
        }

        $courrier->delete();
        $arrive->delete();
        /*  $status = "Supprimer avec succès"; */
        Alert::success('Opération réussie !', 'Le courrier a été supprimé avec succès.');
        /* return redirect()->back()->with("danger", $status); */
        return redirect()->back();
    }

    public function arriveImputation(Request $request, $id)
    {
        $arrive   = Arrive::findOrFail($id);
        $courrier = $arrive->courrier;

        return view("courriers.arrives.imputation-arrive", compact("arrive", "courrier"));
    }

    public function fetch(Request $request)
    {

        if ($request->get('query')) {
            $query = $request->get('query');

            /* $data = DB::table('directions')
                ->where('sigle', 'LIKE', "%{$query}%")
                ->get(); */
            /* $data = DB::table('employees')->join('users', 'users.id', 'employees.users_id')
                ->select('employees.*')
                ->where('users.firstname', 'LIKE', "%{$query}%")
                ->orwhere('users.name', 'LIKE', "%{$query}%")
                ->get(); */

            $data = Employee::join('users', 'users.id', 'employees.users_id')
                ->select('employees.*')
                ->where('users.firstname', 'LIKE', "%{$query}%")
                ->orwhere('users.name', 'LIKE', "%{$query}%")
                ->get();

            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            /* foreach ($data as $direction) {
                $id = $direction->id;
                $sigle = $direction->sigle;
                $employe_id = $direction->chef_id;
                $employe = Employee::find($employe_id);

                $user = User::find($employe->users_id);

                $name = $user->firstname . ' ' . $user->name;

                $output .= '

                <li data-id="' . $id . '" data-chef="' . $name . '" data-employeid="' . $employe->id . '"><a href="#">' . $sigle . '</a></li>
       ';
            } */
            foreach ($data as $employe) {
                $id             = $employe->id;
                $firstname      = $employe->user->firstname;
                $name           = $employe->user->name;
                $direction_name = $employe->direction->name;
                $iddirection    = $employe->direction->id;
                $sigle          = $employe->direction->sigle;

                $direction = $direction_name . ' (' . $sigle . ') ';

                $name = $firstname . ' ' . $name;

                $output .= '

                <li data-id="' . $id . '" data-direction="' . $direction . '" data-iddirection="' . $iddirection . '"><a href="#">' . $name . '</a></li>
       ';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function couponArrive(Request $request)
    {
        $arrive = Arrive::find($request->input('id'));

        $courrier = $arrive->courrier;

        /*  $directions     = Direction::pluck('sigle', 'id'); */

        /* $directions = Direction::pluck('sigle', 'sigle')->all(); */
        /*
        $directions = Direction::whereNotIn('sigle', ['AntZIG', 'AntTH'])
            ->pluck('sigle', 'sigle')
            ->all(); */

        $directions = Direction::where('sigle', 'not like', 'Ant%')
            ->pluck('sigle', 'sigle')
            ->all();

        $arriveDirections  = $courrier->directions->pluck('sigle', 'sigle')->all();
        $arriveDescription = $courrier->description;

        $numero = $courrier->numero_courrier;

        $title = ' Coupon d\'envoi ourrier arrivé n° ' . $numero;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Courier');
        $dompdf->setOptions($options);

        $actions = [
            'Urgent',
            'M\'en parler',
            'Etudes et Avis',
            'Répondre',
            'Suivi',
            'Information',
            'Diffusion',
            'Attribution',
            'Classement',
            'Pour rappel',
        ];

        $dompdf->loadHtml(view('courriers.arrives.arrive-coupon', compact(
            'arrive',
            'courrier',
            'directions',
            'arriveDirections',
            'arriveDescription',
            'title',
            'actions'
        )));

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $anne = date('d');
        $anne = $anne . ' ' . date('m');
        $anne = $anne . ' ' . date('Y');
        $anne = $anne . ' à ' . date('H') . 'h';
        $anne = $anne . ' ' . date('i') . 'min';
        $anne = $anne . ' ' . date('s') . 's';

        $name = 'Courrier arrivé n° ' . $numero . ' du ' . $anne . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function rapports(Request $request)
    {
        $title = 'rapports courriers arrivés';
        return view('courriers.arrives.rapports', compact(
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

        $arrives = Arrive::whereBetween(DB::raw('DATE(created_at)'), [$request->from_date, $request->to_date])->get();

        $count = $arrives->count();

        if ($from_date == $to_date) {
            if (isset($count) && $count < "1") {
                $title = 'aucun courrier arrivé le ' . $from_date;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' courrier arrivé le ' . $from_date;
            } else {
                $title = $count . ' courriers arrivés le ' . $from_date;
            }
        } else {
            if (isset($count) && $count < "1") {
                $title = 'aucun courrier arrivé entre le ' . $from_date . ' et le ' . $to_date;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' courrier arrivé entre le ' . $from_date . ' et le ' . $to_date;
            } else {
                $title = $count . ' courriers arrivés entre le ' . $from_date . ' et le ' . $to_date;
            }
        }

        return view('courriers.arrives.rapports', compact(
            'arrives',
            'from_date',
            'to_date',
            'title'
        ));
    }
    public function generateReport(Request $request)
    {
        $this->validate($request, [
            'numero'     => 'nullable|string',
            'objet'      => 'nullable|string',
            'expediteur' => 'nullable|string',
        ]);

        if ($request?->numero == null && $request->objet == null && $request->expediteur == null) {
            Alert::warning('Attention !', 'Veuillez renseigner au moins un champ pour effectuer la recherche.');
            return redirect()->back();
        }

        $arrives = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
            ->select('arrives.*')
            ->where('arrives.numero_arrive', 'LIKE', "%{$request?->numero}%")
            ->where('courriers.objet', 'LIKE', "%{$request?->objet}%")
            ->where('courriers.expediteur', 'LIKE', "%{$request?->expediteur}%")
            ->distinct()
            ->get();

        $count = $arrives?->count();

        // Récupérer le total des arrivées sans type et les derniers 100 courriers en une seule requête
        $totalCount  = Arrive::where('type', null);
        $total_count = number_format($totalCount->count(), 0, ',', ' ');

        if (isset($count) && $count < "1") {
            $title = 'aucun courrier trouvé';
        } elseif (isset($count) && $count == "1") {
            $title = $count . ' courrier trouvé';
        } else {
            $title = $count . ' courriers trouvés';
        }

        $count_arrives = Arrive::where('type', 'operateur')->count();

        return view('courriers.arrives.index', compact(
            'arrives',
            'count_arrives',
            'total_count',
            'title'
        ));
    }

    public function arrivesop(Request $request)
    {
        $anneeEnCours = date('Y');
        $an           = date('y');

        $numCourrier = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
            ->select('arrives.*')
            ->where('courriers.annee', $anneeEnCours)
            ->get()->last();

        $numDossier = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
            ->select('arrives.*')
            ->where('courriers.annee', $anneeEnCours)
            ->where('arrives.type', 'operateur')
            ->get()->last();

        if (isset($numCourrier) && isset($numDossier)) {
            $numCourrier = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
                ->select('arrives.*')
                ->get()->last()->numero_arrive;

            $numDossier = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
                ->select('arrives.*')
                ->where('arrives.type', 'operateur')
                ->get()->last()->numero_dossier;

            $numCourrier = ++$numCourrier;
        } elseif (isset($numCourrier)) {
            $numCourrier = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
                ->select('arrives.*')
                ->get()->last()->numero_arrive;

            $numCourrier = ++$numCourrier;
        } elseif (isset($numDossier)) {

            $numDossier = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
                ->select('arrives.*')
                ->where('arrives.type', 'operateur')
                ->get()->last()->numero_dossier;
        } else {

            $numCourrier = $an . "0001";
            $numDossier  = "0001";

            $longueur = strlen($numCourrier);

            if ($longueur <= 1) {
                $numCourrier = strtolower("00000" . $numCourrier);
            } elseif ($longueur >= 2 && $longueur < 3) {
                $numCourrier = strtolower("0000" . $numCourrier);
            } elseif ($longueur >= 3 && $longueur < 4) {
                $numCourrier = strtolower("000" . $numCourrier);
            } elseif ($longueur >= 4 && $longueur < 5) {
                $numCourrier = strtolower("00" . $numCourrier);
            } elseif ($longueur >= 5 && $longueur < 6) {
                $numCourrier = strtolower("0" . $numCourrier);
            } else {
                $numCourrier = strtolower($numCourrier);
            }
        }

        /* $arrives = Arrive::orderBy('created_at', 'desc')->get(); */

        $total_count = Arrive::where('type', 'operateur')->get();
        $total_count = number_format($total_count->count(), 0, ',', ' ');

        $arrives = Arrive::where('type', 'operateur')->take(100)
            ->latest()
            ->get();

        $count_arrives           = Arrive::where('type', 'operateur')->count();
        $count_courriers_arrives = Arrive::where('type', null)->count();

        $count_courrier = number_format($arrives?->count(), 0, ',', ' ');

        if ($count_courrier < "1") {
            $title = 'Aucun courrier opérateur';
        } elseif ($count_courrier == "1") {
            $title = $count_courrier . ' courrier opérateur sur un total de ' . $total_count;
        } else {
            $title = $count_courrier . ' derniers courriers opérateur sur un total de ' . $total_count;
        }

        $today = date('Y-m-d');

        $count_today = Arrive::where('type', 'operateur')->where("created_at", "LIKE", "{$today}%")->count();

        return view(
            "courriers.arrives.operateur",
            compact(
                "arrives",
                "count_today",
                "anneeEnCours",
                "numCourrier",
                "title",
                "count_arrives",
                "count_courriers_arrives",
                "numDossier"
            )
        );
    }

    public function mescourriers(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);
        return view("profile.mescourriers", compact("user"));
    }
}
