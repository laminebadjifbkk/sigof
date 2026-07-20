<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UtilisateurAdminCandidatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:langues.voir')->only(['index', 'show']);
        $this->middleware('permission:langues.gerer')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    /*  public function index()
    {
        $utilisateurs = User::with('roles')
            ->whereHas('roles')
            ->orderBy('name')
            ->get();

        return view('parametres.utilisateurs.index', compact('utilisateurs'));
    } */

    /* public function index()
    {
        $candidats = User::with('roles')
            ->whereHas('candidatures')
            ->withCount('candidatures')
            ->orderBy('name')
            ->get();

        return view('parametres.utilisateurs.index', compact('candidats'));
    } */

    public function index()
    {
        /* $utilisateurs = User::with('roles')
            ->role(['YLP', 'JOJ', 'lecture-seule'])
            ->whereHas('candidatures')
            ->withCount('candidatures')
            ->orderBy('name')
            ->get(); */

        $candidatures = Candidature::with('user')
            ->whereHas('user')
            ->get();

        return view('parametres.utilisateurs.index', compact('candidatures'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['YLP', 'JOJ', 'lecture-seule'])->get();

        return view('parametres.utilisateurs.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::defaults()],
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'firstname' => $data['firstname'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        return redirect()->route('utilisateurs.index')->with('success', 'Compte admin créé avec succès.');
    }

    public function edit(User $utilisateur)
    {
        $estCandidat = $utilisateur->candidatures()->exists();

        $roles = Role::whereIn('name', ['YLP', 'JOJ', 'lecture-seule'])->get();
        /* $regions = $estCandidat ? Region::orderBy('nom')->get() : collect(); */

        return view('parametres.utilisateurs.edit', compact('utilisateur', 'roles', 'estCandidat'));
    }

    public function update(Request $request, User $utilisateur)
    {
        $estCandidat = $utilisateur->candidatures()->exists();

        $rules = [
            'firstname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $utilisateur->id,
        ];

        if ($estCandidat) {
            $rules += [
                'civilite' => 'required|in:M.,Mme',
                'telephone' => 'required|string|max:20',
                'date_naissance' => 'required|date',
                'lieu_naissance' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
            ];
        } else {
            $rules += [
                'roles' => 'required|exists:roles,name',
            ];
        }

        $data = $request->validate($rules);

        if ($estCandidat) {
            $utilisateur->update($data);
        } else {
            $utilisateur->update([
                'firstname' => $data['firstname'],
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
        }
        $utilisateur->syncRoles($request->roles);

        return redirect()->route('utilisateurs.index')->with('success', 'Informations mises à jour avec succès.');
    }

    public function destroy(User $utilisateur)
    {
        if ($utilisateur->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $utilisateur->delete();

        return redirect()->route('utilisateurs.index')->with('success', 'Compte supprimé avec succès.');
    }
}
