<?php

namespace App\Http\Controllers;

use App\Models\Evaluateur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluateurController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DIOF|ADIOF|Ingenieur|Evaluateur']);
        $this->middleware("permission:evaluateur-view", ["only" => ["index"]]);
        /* $this->middleware(['permission:arrive-show']); */
        // or with specific guard
        /* $this->middleware(['role_or_permission:super-admin']); */
    }
    public function index()
    {
        $evaluateurs = Evaluateur::orderBy("created_at", "desc")->get();

        return view("evaluateurs.index", compact("evaluateurs"));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name"      => ["required", "string"],
            "lastname"  => ["required", "string"],
            "adresse"   => ["required", "string"],
            "fonction"  => ["required", "string"],
            "email"     => ["nullable", "string", Rule::unique('evaluateurs')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "telephone" => ["required", "string", "size:12", Rule::unique('evaluateurs')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
        ]);

        $evaluateur = Evaluateur::create([
            "name"      => $request->input("name"),
            "lastname"  => $request->input("lastname"),
            "initiale"  => $request->input("initiale"),
            "fonction"  => $request->input("fonction"),
            "email"     => $request->input("email"),
            "telephone" => $request->input("telephone"),
            "adresse"   => $request->input("adresse"),
        ]);

        /* $evaluateur->save(); */

        if ($request->hasFile('scan_cv')) {
            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('scan_cv');

            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('uploads', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $evaluateur->update([
                'scan_cv' => $filePath,
            ]);
        }

        Alert::success('Succès !', 'Enregistrement effectué');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $evaluateur = Evaluateur::find($id);

        $this->validate($request, [
            "name"      => ['required', 'string', 'max:50'],
            "lastname"  => ['required', 'string', 'max:25'],
            "fonction"  => ['required', 'string', 'max:250'],
            "email"     => ['nullable', 'string', 'max:250', Rule::unique(Evaluateur::class)->ignore($id)->whereNull('deleted_at')],
            "telephone" => ['required', 'string', "size:12", Rule::unique(Evaluateur::class)->ignore($id)->whereNull('deleted_at')],
            'adresse'   => ['required', 'string', 'max:250'],
            // --- Nouveaux champs bancaires (facultatifs) ---
            'banque'        => ['nullable', 'string', 'max:200'],
            'numero_compte' => ['nullable', 'string', 'max:200'],
            'rib'           => ['nullable', 'string', 'max:200'],
        ]);

        $evaluateur->update([
            "name"      => $request->input("name"),
            "lastname"  => $request->input("lastname"),
            "fonction"  => $request->input("fonction"),
            "email"     => $request->input("email"),
            "telephone" => $request->input("telephone"),
            "adresse"   => $request->input("adresse"),
            // --- Nouveaux champs ---
            "banque"        => $request->input("banque"),
            "numero_compte" => $request->input("numero_compte"),
            "rib"           => $request->input("rib"),
        ]);

        /* $evaluateur->save(); */
        if ($request->hasFile('scan_cv')) {
            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('scan_cv');

            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('uploads', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $evaluateur->update([
                'scan_cv' => $filePath,
            ]);
        }

        Alert::success('Succès ! ', 'Modification effectuée');

        return redirect()->back();
    }

    public function show($id)
    {
        $evaluateur  = Evaluateur::findOrFail($id);
        $evaluateurs = Evaluateur::orderBy("created_at", "desc")->get();
        return view('evaluateurs.show', compact('evaluateur', 'evaluateurs'));
    }

    public function destroy($id)
    {
        $evaluateur = Evaluateur::find($id);
        $evaluateur->delete();

        Alert::success('Succès !', 'Suppression effectuée');

        return redirect()->back();
    }
}
