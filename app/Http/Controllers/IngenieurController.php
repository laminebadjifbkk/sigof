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
                'nullable', 'string', 'max:25',
                Rule::unique('ingenieurs')->ignore($id)->where(fn($query) => $query->whereNull('deleted_at')),
            ],
            'name'      => [
                'required', 'string', 'max:50',
                Rule::unique('ingenieurs')->ignore($id)->where(fn($query) => $query->whereNull('deleted_at')),
            ],
            'initiale'  => [
                'required', 'string', 'max:25',
                Rule::unique('ingenieurs')->ignore($id)->where(fn($query) => $query->whereNull('deleted_at')),
            ],
            'email'     => [
                'required', 'string', 'max:50',
                Rule::unique('ingenieurs')->ignore($id)->where(fn($query) => $query->whereNull('deleted_at')),
            ],
            'telephone' => [
                'required', 'string', 'size:12',
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
        return view('ingenieurs.show', compact('ingenieur', 'departements', 'modules', 'regions', 'operateurs', 'types_formations', 'ingenieurs'));
    }

    public function destroy($id)
    {
        $ingenieur = Ingenieur::find($id);
        $ingenieur->delete();

        Alert::success('Succès !', 'Suppression effectuée');

        return redirect()->back();
    }
}
