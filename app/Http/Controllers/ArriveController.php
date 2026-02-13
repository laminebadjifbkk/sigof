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
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class ArriveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|courrier|a-courrier|Employe']);
    }

    public function index(Request $request)
    {
        $anneeEnCours = date('Y');  // 2026
        $an           = date('y');  // 26

        // Récupérer le dernier numéro pour l’année en cours
        $lastArrive = Arrive::join('courriers', 'courriers.id', '=', 'arrives.courriers_id')
            ->where('courriers.annee', $anneeEnCours)
            ->orderByDesc('arrives.numero_arrive')
            ->select('arrives.numero_arrive')
            ->first();

        if ($lastArrive && $lastArrive->numero_arrive) {
            $numCourrier = $lastArrive->numero_arrive + 1;
        } else {
            // Premier numéro de l’année
            $numCourrier = $an . "0001";
        }

        // Toujours formater sur 6 caractères (ex: 260001)
        $numCourrier = str_pad($numCourrier, 6, '0', STR_PAD_LEFT);

        /* $arrives      = Arrive::latest()->take(500)->get();
        $totalArrives = number_format($arrives->count(), 0, ',', ' ');

        $today = date('Y-m-d');

        // Compter les arrivées du jour
        $count_today = Arrive::where('created_at', 'LIKE', "{$today}%")
            ->count(); */

        // Total global
        $total = Arrive::count();
        $totalArrives = number_format($total, 0, ',', ' ');

        $query = Arrive::query();

        if ($statut = $request->query('statut')) {
            $query->where('statut', $statut);
        }

        $arrives = $query
            ->latest()
            ->limit(100)
            ->get();

        /* $groupes = Arrive::join('courriers', 'courriers.id', '=', 'arrives.courriers_id')
            ->select('courriers.annee')
            ->selectRaw('COUNT(arrives.id) as total')
            ->groupBy('courriers.annee')
            ->orderBy('courriers.annee', 'desc')
            ->get(); */

        $groupes = Arrive::join('courriers', 'courriers.id', '=', 'arrives.courriers_id')
            ->select('courriers.annee')
            ->selectRaw('COUNT(arrives.id) as total')
            ->groupBy('courriers.annee')
            ->orderBy('courriers.annee', 'desc')
            ->paginate(1); // ← une ligne par page

        $affichees = $arrives?->count();
        $total     = $totalArrives ?? ($arrives instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $arrives->total()
            : $arrives?->count());

        return view(
            "courriers.arrives.index",
            compact(
                "arrives",
                "anneeEnCours",
                "numCourrier",
                "totalArrives",
                "groupes",
                "affichees",
                "total",
            )
        );
    }

    public function parAnnee(Request $request, $annee)
    {
        $query = Arrive::join('courriers', 'courriers.id', '=', 'arrives.courriers_id')
            ->where('courriers.annee', $annee)
            ->select('arrives.*'); // important

        $arrives = $query->latest()->limit(100)->get();

        // Total pour l'année après filtres
        $total = $query->count();
        $totalArrives = number_format($total, 0, ',', ' ');

        $affichees = $arrives?->count();
        $total     = $totalArrives ?? ($arrives instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $arrives->total()
            : $arrives?->count());

        $anneeEnCours = date('Y');  // 2026
        $an           = date('y');  // 26
        $today = date('Y-m-d');

        // Récupérer le dernier numéro pour l’année en cours
        $lastArrive = Arrive::join('courriers', 'courriers.id', '=', 'arrives.courriers_id')
            ->where('courriers.annee', $anneeEnCours)
            ->orderByDesc('arrives.numero_arrive')
            ->select('arrives.numero_arrive')
            ->first();

        if ($lastArrive && $lastArrive->numero_arrive) {
            $numCourrier = $lastArrive->numero_arrive + 1;
        } else {
            // Premier numéro de l’année
            $numCourrier = $an . "0001";
        }

        // Toujours formater sur 6 caractères (ex: 260001)
        $numCourrier = str_pad($numCourrier, 6, '0', STR_PAD_LEFT);

        // Compter les arrivées du jour
        $count_today = Arrive::where('created_at', 'LIKE', "{$today}%")
            ->count();

        return view('courriers.arrives.index_annee', compact(
            "arrives",
            "anneeEnCours",
            "numCourrier",
            "totalArrives",
            "affichees",
            "total",
            "count_today",
        ));
    }

    public function create()
    {

        $anneeEnCours = date('Y');
        $an           = date('y');

        // Récupération du dernier numéro de courrier pour l'année en cours
        $numCourrier = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
            ->select('arrives.*')
            ->where('courriers.annee', $anneeEnCours)
            ->get()->last();

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
        $request->validated();

        $date_reponse = $request->input('date_reponse') ?: null;

        $filePath = null; // Initialisation

        // Upload du scan si présent
        if ($request->hasFile('scan')) {
            $file = $request->file('scan');

            // Nettoyage du nom
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = preg_replace("/\s+/", '-', $filename);
            $filename = time() . '_' . $filename . '.' . $file->getClientOriginalExtension();

            // Stockage
            $filePath = $file->storeAs('courriers', $filename, 'public');
        }

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
            'file'            => $filePath,
            'type'            => 'arrive',
            "user_create_id"  => Auth::user()->id,
            "user_update_id"  => Auth::user()->id,
            'users_id'        => Auth::user()->id,
        ]);

        Arrive::create([
            'numero_arrive' => $request->input('numero_arrive'),
            'courriers_id'  => $courrier->id,
        ]);

        Alert::success("Succès !", "Courrier ajouté avec succès.");

        return redirect()->back();
    }

    public function addCourrierOperateur(ArriveOperateurStoreRequest $request): RedirectResponse
    {
        $request->validated();

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

        Arrive::create([
            'numero_arrive' => $request->input('numero_arrive'),
            'type'          => 'operateur',
            'courriers_id'  => $courrier->id,
        ]);

        Operateur::create([
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

            foreach ($arrive->employees as $employe) {
                $toEmail    = $employe?->user->email;
                $toUserName = ($employe?->user->civilite ?? '') . " " . ($employe?->user->firstname ?? '') . " " . ($employe?->user->name ?? '');

                $safeMessage = "Le <b>Directeur Général</b> de l'ONFP vous a imputé un nouveau courrier. <br>
                    Merci de vous connecter à votre compte (<a href='{$lienApp}'>ici</a>) pour voir les détails
                    ou accéder directement au courrier via <a href='{$lienCourrier}'>ce lien</a>.";

                $subject = "COURRIER ONFP | $objetCourrier";
                $message = strip_tags($safeMessage, '<b><i><p><a><br>'); // Autorise <b>, <i>, <p>, et <a>

                Mail::to($toEmail)->send(new ImputationcourrierMail($message, $subject, $toEmail, $toUserName, $arrive));
            }

            Alert::success('Bravo !', 'Le courrier a été imputé avec succès.');

            return redirect()->back();
        }

        $this->validate($request, [
            "date_arrivee"        => ["required", "date", "size:10", "date_format:Y-m-d"],
            "date_correspondance" => ["required", "date", "size:10", "date_format:Y-m-d"],
            "numero_courrier"     => [
                "nullable",
                "string",
                "min:4",
                "max:100",
                Rule::unique('courriers', 'numero_courrier')
                    ->ignore($arrive->courrier->id)
                    ->whereNull('deleted_at'), // Ignore les courriers supprimés
            ],
            "numero_arrive"       => [
                "required",
                "string",
                "min:4",
                "max:25",
                Rule::unique('arrives', 'numero_arrive')
                    ->ignore($arrive->id)
                    ->whereNull('deleted_at'),
            ],
            "numero_reponse"      => [
                "string",
                "min:4",
                "max:9",
                "nullable",
                Rule::unique('courriers', 'numero_reponse')
                    ->ignore($courrier->id)
                    ->whereNull('deleted_at'),
            ],
            "scan"                => ['sometimes', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10000'],
            "annee"               => ["required", "string"],
            "expediteur"          => ["required", "string"],
            "objet"               => ["required", "string"],
            "date_reponse"        => ["nullable", "date"],
            "observation"         => ["nullable", "string"],
        ]);

        $date_reponse = $request->input('date_reponse') ?: null;

        if ($request->hasFile('scan')) {

            // Suppression de l'ancien fichier si existant
            if (!is_null($arrive->courrier->file)) {
                Storage::disk('public')->delete($arrive->courrier->file);
            }

            // Traitement du nouveau fichier
            $file = $request->file('scan');
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = preg_replace("/\s+/", '-', $filename);
            $filename = time() . '_' . $filename . '.' . $file->getClientOriginalExtension();

            // Stockage
            $filePath = $file->storeAs('courriers', $filename, 'public');

            // Mise à jour du champ dans la base
            $arrive->courrier->file = $filePath;
        }

        if ($arrive->type == 'operateur') {
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
                'legende'         => $request->input('objet'),
                'type'            => 'operateur',
                "user_create_id"  => Auth::user()->id,
                "user_update_id"  => Auth::user()->id,
                'users_id'        => Auth::user()->id,
            ];

            $courrier->update($data);

            $arrive->update([
                'numero_arrive' => $request->input('numero_arrive'),
                'courriers_id'  => $courrier->id,
                'type'          => 'operateur',
            ]);
            Alert::success('Bravo !', 'La mise à jour a été effectuée avec succès.');
            return Redirect::route('arrivesop');
        } else {
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
                'type'          => 'arrive',
            ]);
            Alert::success('Bravo !', 'La mise à jour a été effectuée avec succès.');
            return Redirect::back();
        }
    }
    public function show($id)
    {
        $arrive = Arrive::findOrFail($id);

        $courrier = $arrive?->courrier;

        $user_create = User::find($courrier->user_create_id);
        $user_update = User::find($courrier->user_update_id);

        $user_create_name = $user_create->firstname . ' ' . $user_create->name;
        $user_update_name = $user_update->firstname . ' ' . $user_update->name;

        return view(
            "courriers.arrives.show",
            compact(
                "arrive",
                "courrier",
                "user_create_name",
                "user_update_name"
            )
        );
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

        /* $directions = Direction::where('sigle', 'not like', 'Ant%')
            ->pluck('sigle', 'sigle')
            ->all(); */

        $directions = Direction::where('sigle', 'not like', 'Ant%')
            ->where('sigle', 'not like', 'Pole%')
            ->pluck('sigle', 'sigle')
            ->all();

        /* $arriveDirections  = $courrier->directions->pluck('sigle', 'sigle')->all(); */
        /* $arriveDirections  = $courrier->directions->pluck('sigle')->values()->toArray(); */
        $arriveDirections = $courrier->directions
            ->pluck('sigle') // DG, DAF, etc.
            ->values()
            ->toArray();

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
            'Circularisation',
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
        /* $totalCount  = Arrive::where('type', null);
        $total_count = number_format($totalCount->count(), 0, ',', ' ');

        if (isset($count) && $count < "1") {
            $title = 'aucun courrier trouvé';
        } elseif (isset($count) && $count == "1") {
            $title = $count . ' courrier trouvé';
        } else {
            $title = $count . ' courriers trouvés';
        }

        $count_arrives = Arrive::where('type', 'operateur')->count(); */

        $totalArrives = number_format($arrives->count(), 0, ',', ' ');

        $affichees = $arrives?->count();
        $total     = $totalArrives ?? ($arrives instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $arrives->total()
            : $arrives?->count());

        return view('courriers.arrives.index', compact(
            'arrives',
            /* 'count_arrives', */
            'totalArrives',
            'affichees',
            'total',
            /* 'title' */
        ));
    }

    public function arrivesop(Request $request)
    {
        $anneeEnCours = date('Y');
        $an           = date('y');

        // Récupération du dernier numéro de courrier pour l'année en cours
        $numCourrier = Arrive::join('courriers', 'courriers.id', '=', 'arrives.courriers_id')
            ->select('arrives.*')
            ->where('courriers.annee', $anneeEnCours)
            ->orderByDesc('arrives.id') // ou le champ qui définit l'ordre chronologique
            ->first();

        if ($numCourrier) {
            // Si un courrier existe, incrémenter son numéro
            $numCourrier = ++$numCourrier->numero_arrive;
        } else {
            // Si aucun courrier n'existe, initialiser avec l'année et le numéro 0001
            $numCourrier = $an . "0001";
        }

        // Mise en forme du numéro de courrier en ajoutant des zéros au début
        $numCourrier = str_pad($numCourrier, 6, '0', STR_PAD_LEFT);

        $arrives      = Arrive::where('type', 'operateur');
        $totalArrives = number_format($arrives->count(), 0, ',', ' ');
        $arrives      = $arrives->latest()->take(100)->get();

        $numDossier = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
            ->select('arrives.*')
            ->where('courriers.annee', $anneeEnCours)
            ->where('arrives.type', 'operateur')
            ->get()->last();

        /* if (isset($numCourrier) && isset($numDossier)) {
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
        } */

        /* $arrives = Arrive::orderBy('created_at', 'desc')->get(); */

        $total_count = Arrive::where('type', 'operateur')->get();
        $total_count = number_format($total_count->count(), 0, ',', ' ');

        $arrives = Arrive::where('type', 'operateur')->take(100)
            ->latest()
            ->get();

        $count_arrives           = Arrive::where('type', 'operateur')->count();
        $count_courriers_arrives = Arrive::where('type', null)->count();

        $count_courrier = number_format($arrives?->count(), 0, ',', ' ');

        /* if ($count_courrier < "1") {
            $title = 'Aucun courrier opérateur';
        } elseif ($count_courrier == "1") {
            $title = $count_courrier . ' courrier opérateur sur un total de ' . $total_count;
        } else {
            $title = $count_courrier . ' derniers courriers opérateur sur un total de ' . $total_count;
        } */

        $affichees = $arrives?->count();
        $total     = $totalArrives ?? ($arrives instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $arrives->total()
            : $arrives?->count());

        $today = date('Y-m-d');

        $count_today = Arrive::where('type', 'operateur')->where("created_at", "LIKE", "{$today}%")->count();

        return view(
            "courriers.arrives.operateur",
            compact(
                "arrives",
                "count_today",
                "anneeEnCours",
                "numCourrier",
                "affichees",
                "total",
                "totalArrives",
                "count_arrives",
                "count_courriers_arrives",
                "numDossier"
            )
        );
    }

    private function diffHumanReadable($date)
    {
        $aujourdhui = Carbon::today();
        $diff       = $date->diff($aujourdhui);

        $ans   = $diff->y;
        $mois  = $diff->m;
        $jours = $diff->d;

        $parts = [];
        if ($ans > 0) {
            $parts[] = $ans . ' ' . Str::plural('an', $ans);
        }
        if ($mois > 0) {
            $parts[] = $mois . ' mois'; // "mois" ne change pas au pluriel
        }
        if ($jours > 0) {
            $parts[] = $jours . ' ' . Str::plural('jour', $jours);
        }

        return implode(' ', $parts);
    }

    public function getBadgeDateAttribute()
    {
        if (! $this->jour_imputation) {
            return '<span class="badge bg-danger">Date non disponible</span>';
        }

        $date = Carbon::parse($this->jour_imputation);

        if ($date->isToday()) {
            return '<span class="badge bg-success">Aujourd\'hui</span>';
        }

        if ($date->isYesterday()) {
            return '<span class="badge bg-warning">Hier</span>';
        }

        if ($date->diffInDays(Carbon::today()) < 7) {
            return '<span class="badge bg-primary">Il y a ' . $date->diffInDays(Carbon::today()) . ' jours</span>';
        }

        // Diff détaillée en années, mois, jours
        $diff = $date->diff(Carbon::today());

        $ans   = $diff->y;
        $mois  = $diff->m;
        $jours = $diff->d;

        // On affiche tout, même les 0 (ex: "0 an 1 mois 10 jours")
        $parts   = [];
        $parts[] = $ans . ' ' . Str::plural('an', $ans);
        $parts[] = $mois . ' mois'; // "mois" reste invariable
        $parts[] = $jours . ' ' . Str::plural('jour', $jours);

        return '<span class="badge bg-secondary">Il y a ' . implode(' ', $parts) . '</span>';
    }

    public function mescourriers(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);
        $employee = $user?->employee;
        $arrives  = $employee?->arrives()->orderBy('created_at', 'desc')->get();
        return view("profile.mescourriers", compact("user", "employee", "arrives"));
    }
}
