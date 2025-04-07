<?php
namespace App\Http\Controllers;

use App\Models\Commissionmembre;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CommissionmembreController extends Controller
{
    public function index()
    {
        $membres = Commissionmembre::get();

        return view('operateurs.commissionmembres.index', compact('membres'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "civilite"  => "required|string",
            "prenom"    => "required|string",
            "nom"       => "required|string",
            "fonction"  => "required|string",
            "structure" => "required|string",
            "email"     => "required|email",
            "telephone" => "required|string|size:12",
        ]);

        $membre = Commissionmembre::create([
            "civilite"  => $request->input("civilite"),
            "prenom"    => $request->input("prenom"),
            "nom"       => $request->input("nom"),
            "fonction"  => $request->input("fonction"),
            "structure" => $request->input("structure"),
            "email"     => $request->input("email"),
            "telephone" => $request->input("telephone"),
        ]);

        Alert::success('Succès !', 'Enregistrement effectué avec succès');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "civilite"  => "required|string",
            "prenom"    => "required|string",
            "nom"       => "required|string",
            "fonction"  => "required|string",
            "structure" => "required|string",
            "email"     => "required|email",
            "telephone" => "required|string|size:12",
        ]);

        $membre = Commissionmembre::findOrFail($id);

        $membre->update([
            "civilite"  => $request->input("civilite"),
            "prenom"    => $request->input("prenom"),
            "nom"       => $request->input("nom"),
            "fonction"  => $request->input("fonction"),
            "structure" => $request->input("structure"),
            "email"     => $request->input("email"),
            "telephone" => $request->input("telephone"),
        ]);

        Alert::success('Succès !', 'Modification effectuée avec succès');

        return redirect()->back();
    }

    public function show($id)
    {
        $membre = Commissionmembre::findOrFail($id);

        return view('operateurs.commissionmembres.show', compact('membre'));
    }

    public function destroy($id)
    {
        $membre = Commissionmembre::findOrFail($id);
        $membre->delete();

        Alert::success('Opération réussie !', 'La suppression a été effectuée avec succès.');

        return redirect()->back();
    }
}
