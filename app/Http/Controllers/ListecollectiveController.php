<?php

namespace App\Http\Controllers;

use App\Models\Collective;
use App\Models\Listecollective;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class ListecollectiveController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|Demandeur|DIOF|Ingenieur|ADIOF|DEC|ADEC|Operateur|SUIVI'])
            ->except(['index', 'store', 'generateReport']);
    }

    public function index()
    {
        $listecollectives      = Listecollective::count();
        $totalListecollective = number_format($listecollectives, 0, ',', ' ');

        $listecollectives = Listecollective::latest()->limit(500)->get();

        return view(
            'listecollectives.index',
            compact(
                'listecollectives',
                'totalListecollective'
            )
        );
    }

    public function store(Request $request)
    {
        // 🔹 Nettoyer la CIN (supprimer tous les espaces)
        $cin = preg_replace('/\s+/', '', $request->cin);

        // 🔹 Injecter la version nettoyée dans la requête
        $request->merge(['cin' => $cin]);

        // 🔹 Validation
        $validator = Validator::make($request->all(), [
            "type_piece"     => "required|in:cni,extrait,passeport",
            "civilite"       => "required|string",
            "firstname"      => "required|string",
            "name"           => "required|string",
            'date_naissance' => ['required', 'date_format:d/m/Y'],
            "lieu_naissance" => "required|string",
            "module"         => "required|string",
            "niveau_etude"   => "required|string",
            "experience"     => "required|string",
            "telephone"      => "required|string|size:9",
            "cin" => [
                "required",
                "string",
                Rule::unique('listecollectives', 'cin')
                    ->whereNull('deleted_at')
            ],
        ]);

        // 🔹 Validation conditionnelle selon type_piece
        $validator->sometimes('cin', ['regex:/^[A-Z0-9]{13,14}$/i'], fn($input) => $input->type_piece === 'cni');
        $validator->sometimes('cin', ['regex:/^[0-9\/]{10}$/'], fn($input) => $input->type_piece === 'extrait');
        $validator->sometimes('cin', ['regex:/^[A-Z0-9]{9}$/i'], fn($input) => $input->type_piece === 'passeport');

        $data = $validator->validate();

        // 🔹 Conversion de la date de naissance
        $date_naissance = Carbon::createFromFormat('d/m/Y', $request->input('date_naissance'));

        // 🔹 Création du membre
        Listecollective::create([
            'cin'                  => $data['cin'], // stocké sans espace
            'civilite'             => $request->input('civilite'),
            'prenom'               => format_proper_name($request->input('firstname')),
            'nom'                  => remove_accents_uppercase($request->input('name')),
            'date_naissance'       => $date_naissance,
            'lieu_naissance'       => remove_accents_uppercase($request->input('lieu_naissance')),
            'niveau_etude'         => $request->input('niveau_etude'),
            'telephone'            => $request->input('telephone'),
            'experience'           => $request->input('experience'),
            'autre_experience'     => $request->input('autre_experience'),
            'details'              => $request->input('details'),
            'statut'               => 'Nouvelle',
            'collectivemodules_id' => $request->input('module'),
            'collectives_id'       => $request->input('collective'),
        ]);

        Alert::success('Succès !', 'Enregistrement effectué avec succès.');
        return redirect()->back();
    }

    public function edit(Listecollective $listecollective)
    {
        $user             = Auth::user();
        $rolesUtilisateur = $user->roles->pluck('name')->toArray();
        $rolesAutorises   = ['super-admin', 'admin', 'DIOF', 'ADIOF', 'Ingenieur'];

        // Si l'utilisateur a un rôle autorisé OU si le statut est "Nouvelle"
        if (
            array_intersect($rolesUtilisateur, $rolesAutorises) ||
            $listecollective->statut === 'Nouvelle'
        ) {
            return view("collectives.updateliste", compact("listecollective"));
        }

        Alert::warning('Désolé !', 'Vous n\'avez pas l\'autorisation de modifier cette collective.');
        return redirect()->back();
    }

    public function update(Request $request, Listecollective $listecollective)
    {
        // 🔹 Nettoyer la CIN (supprimer tous les espaces)
        $cin = preg_replace('/\s+/', '', $request->cin);

        // 🔹 Injecter la version nettoyée dans la requête
        $request->merge(['cin' => $cin]);

        // 🔹 Validation
        $validator = Validator::make($request->all(), [
            "type_piece"     => "required|in:cni,extrait,passeport",
            "civilite"       => "required|string",
            "firstname"      => "required|string",
            "name"           => "required|string",
            'date_naissance' => ['required', 'regex:/^\d{2}\/\d{2}\/\d{4}$/'],
            "lieu_naissance" => "required|string",
            "module"         => "required|string",
            "niveau_etude"   => "required|string",
            "experience"     => "required|string",
            "telephone"      => "required|string|size:9",
            "cin" => [
                "required",
                "string",
                Rule::unique('listecollectives', 'cin')
                    ->whereNull('deleted_at')
                    ->ignore($listecollective->id)
            ],
        ]);

        // 🔹 Validation conditionnelle selon type_piece
        $validator->sometimes('cin', ['regex:/^[A-Z0-9]{13,14}$/i'], fn($input) => $input->type_piece === 'cni'); // CNI avec lettres/chiffres
        $validator->sometimes('cin', ['regex:/^[0-9\/]{10}$/'], fn($input) => $input->type_piece === 'extrait');
        $validator->sometimes('cin', ['regex:/^[A-Z0-9]{9}$/i'], fn($input) => $input->type_piece === 'passeport');

        $data = $validator->validate();

        // 🔹 Conversion de la date
        /* $date_naissance = Carbon::createFromFormat('d/m/Y', $request->input('date_naissance')); */

        /* try {
            $date_naissance = Carbon::createFromFormat('d/m/Y', $request->input('date_naissance'));
        } catch (\Exception $e) {
            return back()->withErrors([
                'date_naissance' => 'Date invalide.'
            ])->withInput();
        }
 */
        /* $date_naissance = Carbon::parse($request->input('date_naissance')); */

        $inputDate = $request->input('date_naissance');

      /*   if ($inputDate === '00/00/1985') {
            $date_naissance = null; // ou une valeur spéciale
        } else { */
            try {
                $date_naissance = Carbon::createFromFormat('d/m/Y', $inputDate);
            } catch (\Exception $e) {
                return back()->withErrors([
                    'date_naissance' => 'Date invalide.'
                ])->withInput();
            }
        /* } */
        // 🔹 Mise à jour du membre
        $listecollective->update([
            'cin'                  => $data['cin'], // stocké sans espace
            'civilite'             => $request->input('civilite'),
            'prenom'               => format_proper_name($request->input('firstname')),
            'nom'                  => remove_accents_uppercase($request->input('name')),
            'date_naissance'       => $date_naissance,
            'lieu_naissance'       => remove_accents_uppercase($request->input('lieu_naissance')),
            'niveau_etude'         => $request->input('niveau_etude'),
            'telephone'            => $request->input('telephone'),
            'experience'           => $request->input('experience'),
            'autre_experience'     => $request->input('autre_experience'),
            'details'              => $request->input('details'),
            /* 'statut'               => $request->input('statut'), */
            'collectivemodules_id' => $request->input('module'),
            'collectives_id'       => $listecollective->collective->id,
        ]);

        Alert::success("Succès !", "Modification effectuée avec succès");

        return Redirect::back();
    }

    public function show(Listecollective $listecollective)
    {
        /* $listecollective = Listecollective::find($id); */

        $collective = Collective::findOrFail($listecollective->collectives_id);

        return view("listecollectives.show", compact("listecollective", "collective"));
    }

    public function destroy(Listecollective $listecollective)
    {
        /* $listecollective = Listecollective::find($id); */

        if (! empty($listecollective->formations_id)) {
            Alert::warning('Désolé !', 'Action impossible.');
            return redirect()->back();
        } else {
            $listecollective->delete();
            Alert::success('Succès !', 'Le demandeur a été supprimée avec succès.');
            return redirect()->back();
        }
    }

    public function Validatelistecollective($id)
    {
        $listecollective = Listecollective::findOrFail($id);

        $listecollective->update([
            'statut' => 'Conforme',
        ]);

        $listecollective->save();

        Alert::success('Succès !', 'Demande jugée conforme');
        return redirect()->back();
    }

    public function generateReport(Request $request)
    {
        // Validation des champs (tous optionnels)
        $request->validate([
            'cin'       => 'nullable|string',
            'name'      => 'nullable|string',
            'firstname' => 'nullable|string',
            'telephone' => 'nullable|string',
        ]);

        // Vérifier qu'au moins un champ est rempli
        if (! collect($request->only(['cin', 'name', 'firstname', 'telephone']))->filter()->isNotEmpty()) {
            Alert::warning('Attention', 'Veuillez renseigner au moins un champ pour effectuer une recherche.');
            return redirect()->back();
        }

        // Construire la requête avec filtre
        $query = Listecollective::query();

        if ($request->filled('firstname')) {
            $query->where('prenom', 'LIKE', "%{$request->firstname}%");
        }

        if ($request->filled('name')) {
            $query->where('nom', 'LIKE', "%{$request->name}%");
        }

        if ($request->filled('cin')) {
            $query->where('cin', 'LIKE', "%{$request->cin}%");
        }

        if ($request->filled('telephone')) {
            $query->where('telephone', 'LIKE', "%{$request->telephone}%");
        }

        // Pagination au lieu de get() pour éviter les problèmes de mémoire
        $listecollectives = $query->get(); // 50 par page, à adapter

        $totalListecollective = $query->count(); // total filtré
        $totalListecollective = number_format($totalListecollective, 0, ',', ' ');

        // Retourner la vue avec pagination
        return view('listecollectives.index', compact('listecollectives', 'totalListecollective'));
    }
}
