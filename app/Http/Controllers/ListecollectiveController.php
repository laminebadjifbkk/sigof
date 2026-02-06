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

class ListecollectiveController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|Demandeur|DIOF|Ingenieur|ADIOF|DEC|ADEC|Operateur'])
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
        $this->validate($request, [
            'cin'            => [
                'required',
                'string',
                'min:16',
                'max:17',
                Rule::unique(Listecollective::class)->whereNull('deleted_at'),
            ],
            "civilite"       => "required|string",
            "firstname"      => "required|string",
            "name"           => "required|string",
            'date_naissance' => ['nullable', 'date_format:d/m/Y'],
            "lieu_naissance" => "required|string",
            "module"         => "required|string",
            "niveau_etude"   => "nullable|string",
            "telephone"      => "nullable|string|min:9|max:12",
        ]);

        $dateString     = $request->input('date_naissance');
        $date_naissance = Carbon::createFromFormat('d/m/Y', $dateString);

        $membre = Listecollective::create([
            'cin'                  => $request->input('cin'),
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

        $membre->save();

        Alert::success('Succès !', 'enregistrement effectué avec succès.');

        return redirect()->back();
    }

    /*     public function edit(Listecollective $listecollective)
    {
        foreach (Auth::user()->roles as $key => $role) {
        }

        if ($listecollective->statut != 'Nouvelle' && ! empty($role?->name) && ($role?->name != 'super-admin')) {
            Alert::warning('Désolé !', 'Impossible de modifier ce demandeur.');
            return redirect()->back();
        } else {
            return view("collectives.updateliste", compact("listecollective"));
        }
    } */

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
        $this->validate($request, [
            'cin'            => [
                'required',
                'string',
                'min:16',
                'max:17',
                Rule::unique(Listecollective::class)->ignore($listecollective->id)->whereNull('deleted_at'),
            ],
            "civilite"       => "required|string",
            "firstname"      => "required|string",
            "name"           => "required|string",
            'date_naissance' => ['nullable', 'date_format:d/m/Y'],
            "lieu_naissance" => "required|string",
            "module"         => "required|string",
            "niveau_etude"   => "nullable|string",
            "telephone"      => "nullable|string|min:9|max:12",
        ]);

        /* $listecollective = Listecollective::find($id); */
        $dateString     = $request->input('date_naissance');
        $date_naissance = Carbon::createFromFormat('d/m/Y', $dateString);

        $listecollective->update([
            'cin'                  => $request->input('cin'),
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

        $listecollective->save();

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
